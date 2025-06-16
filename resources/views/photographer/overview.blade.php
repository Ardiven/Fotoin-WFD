@extends('layout.dashboard')

@section('title', 'FotoIn Dashboard - Overview')

@section('page-header')
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-white">Dashboard Overview</h2>
        <button class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
            <i class="fas fa-download mr-2"></i>Export Report
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="stats-card border border-white/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/60 text-sm">Total Bookings</p>
                        <p class="text-white text-2xl font-bold">{{$totalBookings}}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-check text-blue-400"></i>
                    </div>
                </div>
            </div>
            <div class="stats-card border border-white/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/60 text-sm">This Month Bookings</p>
                        <p class="text-white text-2xl font-bold">{{$totalBookingsThisMonth}}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-check text-blue-400"></i>
                    </div>
                </div>
            </div>
            <div class="stats-card border border-white/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                         <p class="text-white/60 text-sm">This Month Revenue</p>
                        <p class="text-white text-2xl font-bold">Rp. {{number_format($totalRevenueThisMonth, 0, ',', '.')}}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-400"></i>
                    </div>
                </div>
            </div>
            <div class="stats-card border border-white/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/60 text-sm">Total Revenue</p>
                        <p class="text-white text-2xl font-bold">Rp. {{number_format($totalRevenue, 0, ',', '.')}}</p>
                    </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .glass-effect {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .content-glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .stats-card {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        
    })
</script>
@endpush