<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index(Package $package){
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

}
