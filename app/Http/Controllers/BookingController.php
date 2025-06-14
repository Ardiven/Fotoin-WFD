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
        return view('customer.booking', compact('package', 'photographer'));
    }
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'photographer_id' => 'required|exists:photographers,id',
            'package_id' => 'required|exists:packages,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'location_type' => 'required|in:studio,outdoor,client_home,venue',
            'location' => 'required|string|max:255',
            'client_name' => 'required|string|max:100',
            'client_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ], [
            'date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu',
            'photographer_id.exists' => 'Photographer tidak ditemukan',
            'package_id.exists' => 'Package tidak ditemukan',
            'location_type.in' => 'Tipe lokasi tidak valid',
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
            $existingBooking = Booking::where('photographer_id', $request->photographer_id)
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
            $package = Package::findOrFail($request->package_id);
            
            // Create booking
            $booking = Booking::create([
                'photographer_id' => $request->photographer_id,
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
            
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat!',
                'booking' => $booking,
                'redirect_url' => route('payment.create', ['booking' => $booking->id])
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ], 500);
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
