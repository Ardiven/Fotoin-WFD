<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
         $photographerId = auth()->id();

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $query = Booking::whereHas('package', function ($q) use ($photographerId) {
            $q;
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


        return view('admin.index', compact(
            'totalBookings',
            'totalBookingsThisMonth',
            'totalRevenue',
            'totalRevenueThisMonth'
        ));
    }
    public function indexUser(Request $request){
        $query = User::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        // if ($request->has('role') && $request->role) {
        //     $query->where('role', $request->role);
        // }
        
        // Filter by status
        // if ($request->has('status') && $request->status !== '') {
        //     $query->where('is_active', $request->status);
        // }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.user.index', compact('users'));
    }
    public function indexCity(){
         $cities = City::latest()->paginate(10);
        
        return view('admin.city.index', compact('cities'));
    }
    public function indexSpecialty(){
         $specialties = DB::table('specialties')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.specialty.index', compact('specialties'));
    }
}
