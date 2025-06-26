<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashPhotoController extends Controller
{
    public function index(){
        $photographerId = auth()->id();

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $query = Booking::whereHas('package', function ($q) use ($photographerId) {
            $q->where('user_id', $photographerId);
        });

        // Total Booking (confirmed)
        $totalBookings = (clone $query)->whereIn('status', ['confirmed', 'completed'])->count();

        // Total Booking Bulan Ini
        $totalBookingsThisMonth = (clone $query)
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Total Revenue (confirmed)
        $totalRevenue = (clone $query)
            ->where('status', 'completed')
            ->sum('total_price');

        // Total Revenue Bulan Ini (confirmed)
        $totalRevenueThisMonth = (clone $query)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_price');

        return view('photographer.overview', compact(
            'totalBookings',
            'totalBookingsThisMonth',
            'totalRevenue',
            'totalRevenueThisMonth'
        ));
    }
}
