<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->hasRole('customer')){ 
            
        
        // Get current user's bookings
        $query = Booking::with(['package.user', 'payment']) // Load package dan fotografer
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by status
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search functionality
        $search = $request->get('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('package', function($packageQuery) use ($search) {
                    $packageQuery->where('name', 'ilike', '%' . $search . '%')
                        ->orWhereHas('user', function($userQuery) use ($search) {
                            $userQuery->where('name', 'ilike', '%' . $search . '%');
                        });
                })
                ->orWhere('location', 'ilike', '%' . $search . '%')
                ->orWhere('booking_number', 'ilike', '%' . $search . '%');
            });
        }

        // Paginate results
        $bookings = $query->paginate(10);

        // Data yang dikirim ke view
        return view('customer.booking.index', compact('bookings'));
    }elseif(Auth::user()->hasRole('photographer')) {
        $photographerId = Auth::id();
        
        // Build query for bookings
        $query = Booking::with(['user', 'package'])
            ->whereHas('package', function($q) use ($photographerId) {
                $q->where('user_id', $photographerId);
            });

        // Apply status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'ilike', "%{$search}%")
                  ->orWhere('client_name', 'ilike', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        // Order by created date, with urgent bookings first
        $bookings = $query
    ->orderByRaw("
        CASE 
            WHEN status = 'confirmed' AND date = CURRENT_DATE THEN 1
            WHEN status = 'confirmed' AND date = CURRENT_DATE + INTERVAL '1 day' THEN 2
            WHEN status = 'pending' THEN 3
            ELSE 4
        END
    ")
    ->orderBy('date')
    ->orderByDesc('created_at')
    ->limit(10)
    ->offset(0)
    ->get();



        // Calculate statistics
        $stats = $this->getBookingStats($photographerId);

        return view('photographer.booking.index', compact('bookings', 'stats'));
    }
    }

    public function confirm(Request $request, $id)
        {
              $booking = Booking::findOrFail($id);
            // Validasi input optional dari form
            $request->validate([
                'confirmation_message' => 'nullable|string|max:1000',
            ]);

            // Update status booking jadi confirmed
            $booking->status = 'confirmed';
            $booking->save();

            // (Opsional) Kirim notifikasi ke client
            // Notification::route(...)->notify(new BookingConfirmed(...));

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dikirim!',
            ]);

        }

    public function complete(Request $request, $id){
        
              $booking = Booking::findOrFail($id);
            // Validasi input optional dari form
            $request->validate([
                'confirmation_message' => 'nullable|string|max:1000',
            ]);

            // Update status booking jadi confirmed
            $booking->status = 'completed';
            $booking->save();

            // (Opsional) Kirim notifikasi ke client
            // Notification::route(...)->notify(new BookingConfirmed(...));

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dikirim!',
            ]);
    }


    public function create(Package $package){
        $photographer = User::find($package->user_id);
        return view('customer.booking.create', compact('package', 'photographer'));
    }
    public function store(Request $request)
    {
         $package = Package::findOrFail($request->package_id);
        // Validasi input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'location_type' => 'required|in:studio,outdoor,client_home,venue',
            'location' => 'required|string|max:255',
            'client_name' => 'required|string|max:100',
            'client_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Cek availability
            $existingBooking = Booking::whereHas('package', function($query) use ($package) {
            $query->where('user_id', $package->user_id);
            })
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->where('status', '!=', 'cancelled')
            ->first();
                
            if ($existingBooking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Waktu tersebut sudah dibooking. Silakan pilih waktu lain.'
                ], 409);
            }
            
            // Get package price
            
            
            // Create booking
            $booking = Booking::create([
                'user_id' => $request->user_id,
                'package_id' => $request->package_id,
                'date' => $request->date,
                'time' => $request->time,
                'location_type' => $request->location_type,
                'location' => $request->location,
                'client_name' => $request->client_name,
                'client_phone' => $request->client_phone,
                'notes' => $request->notes,
                'total_price' => $package->price,
                'status' => 'pending',
                'booking_number' => $this->generateBookingNumber(),
            ]);
            
            DB::commit();
            
            return redirect()->route('customer.payment', $booking)->with('success', 'Booking berhasil dibuat.');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('customer.booking.create', $package)->with('error', $e);
        }
    }
    
    
    /**
     * Generate unique booking number
     */
    private function generateBookingNumber()
    {
        $prefix = 'BK';
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $date . $random;
    }
    public function payRoute(Booking $booking){
        if ($booking->payment) {
           return redirect()->route('customer.payment.pay', $booking->payment); 
        }else{
            return redirect()->route('customer.payment', $booking);
        }
    }
    private function getBookingStats($photographerId)
    {
        $baseQuery = Booking::whereHas('package', function($q) use ($photographerId) {
            $q->where('user_id', $photographerId);
        });

        // Basic status counts
        $statusStats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        // Time-based statistics
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $timeStats = [
            'today' => $baseQuery->whereDate('date', $today)->count(),
            'this_week' => $baseQuery->whereBetween('date', [$thisWeek, $thisWeek->copy()->endOfWeek()])->count(),
            'this_month' => $baseQuery->whereBetween('date', [$thisMonth, $thisMonth->copy()->endOfMonth()])->count(),
            'this_year' => $baseQuery->whereBetween('date', [$thisYear, $thisYear->copy()->endOfYear()])->count(),
        ];

        // Revenue statistics
        $revenueStats = [
            'total_revenue' => $baseQuery->where('status', 'completed')->sum('total_price'),
            'pending_revenue' => $baseQuery->whereIn('status', ['pending', 'confirmed'])->sum('total_price'),
            'monthly_revenue' => $baseQuery->where('status', 'completed')
                ->whereBetween('date', [$thisMonth, $thisMonth->copy()->endOfMonth()])
                ->sum('total_price'),
            'yearly_revenue' => $baseQuery->where('status', 'completed')
                ->whereBetween('date', [$thisYear, $thisYear->copy()->endOfYear()])
                ->sum('total_price'),
        ];

        // Payment statistics
        $paymentStats = [
            'paid' => $baseQuery->where('payment_status', 'completed')->count(),
            'pending_payment' => $baseQuery->where('payment_status', 'pending')->count(),
            'processing_payment' => $baseQuery->where('payment_status', 'processing')->count(),
            'failed_payment' => $baseQuery->where('payment_status', 'failed')->count(),
        ];

        // Upcoming bookings (next 7 days)
        $upcomingStats = [
            'upcoming_week' => $baseQuery->where('status', 'confirmed')
                ->whereBetween('date', [
                    $today,
                    $today->copy()->addDays(7)
                ])->count(),
            'urgent_today' => $baseQuery->where('status', 'confirmed')
                ->whereDate('date', $today)->count(),
            'urgent_tomorrow' => $baseQuery->where('status', 'confirmed')
                ->whereDate('date', $today->copy()->addDay())->count(),
        ];

        // Performance metrics
        $performanceStats = [
            'completion_rate' => $statusStats['total'] > 0 
                ? round(($statusStats['completed'] / $statusStats['total']) * 100, 2) 
                : 0,
            'confirmation_rate' => $statusStats['total'] > 0 
                ? round((($statusStats['confirmed'] + $statusStats['completed']) / $statusStats['total']) * 100, 2) 
                : 0,
            'cancellation_rate' => $statusStats['total'] > 0 
                ? round(($statusStats['cancelled'] / $statusStats['total']) * 100, 2) 
                : 0,
        ];

        // Average booking value
        $avgStats = [
            'avg_booking_value' => $statusStats['total'] > 0 
                ? round($baseQuery->avg('total_price'), 2) 
                : 0,
            'avg_completed_value' => $statusStats['completed'] > 0 
                ? round($baseQuery->where('status', 'completed')->avg('total_price'), 2) 
                : 0,
        ];

        // Monthly trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $monthName = $monthStart->format('M Y');
            
            $monthlyTrend[] = [
                'month' => $monthName,
                'bookings' => $baseQuery->whereBetween('date', [$monthStart, $monthEnd])->count(),
                'completed' => $baseQuery->where('status', 'completed')
                    ->whereBetween('date', [$monthStart, $monthEnd])->count(),
                'revenue' => $baseQuery->where('status', 'completed')
                    ->whereBetween('date', [$monthStart, $monthEnd])->sum('total_price'),
            ];
        }

        // Popular packages
        $popularPackages = Package::where('user_id', $photographerId)
            ->withCount(['bookings as total_bookings'])
            ->withCount(['bookings as completed_bookings' => function($q) {
                $q->where('status', 'completed');
            }])
            ->withSum(['bookings as total_revenue' => function($q) {
                $q->where('status', 'completed');
            }], 'total_price')
            ->orderBy('total_bookings', 'desc')
            ->limit(5)
            ->get()
            ->map(function($package) {
                return [
                    'name' => $package->name,
                    'total_bookings' => $package->total_bookings,
                    'completed_bookings' => $package->completed_bookings,
                    'total_revenue' => $package->total_revenue ?? 0,
                ];
            });

        // Recent activities
        $recentActivities = $baseQuery->with(['user', 'package'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'client_name' => $booking->user->name ?? $booking->client_name,
                    'package_name' => $booking->package->name,
                    'status' => $booking->status,
                    'date' => $booking->date,
                    'updated_at' => $booking->updated_at,
                ];
            });

        return array_merge(
            $statusStats,
            [
                'time' => $timeStats,
                'revenue' => $revenueStats,
                'payment' => $paymentStats,
                'upcoming' => $upcomingStats,
                'performance' => $performanceStats,
                'average' => $avgStats,
                'monthly_trend' => $monthlyTrend,
                'popular_packages' => $popularPackages,
                'recent_activities' => $recentActivities,
            ]
        );
    }

}
