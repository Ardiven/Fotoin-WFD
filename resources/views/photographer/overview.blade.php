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
                        <p class="text-white text-2xl font-bold">142</p>
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
                        <p class="text-white text-2xl font-bold">$8,420</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-400"></i>
                    </div>
                </div>
            </div>
            <div class="stats-card border border-white/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/60 text-sm">Portfolio Views</p>
                        <p class="text-white text-2xl font-bold">2,847</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-eye text-purple-400"></i>
                    </div>
                </div>
            </div>
            <div class="stats-card border border-white/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/60 text-sm">Client Rating</p>
                        <p class="text-white text-2xl font-bold">4.9</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <h3 class="text-white text-lg font-semibold mb-4">Recent Activity</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-white/10">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-calendar text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-white">New booking confirmed</p>
                            <p class="text-white/60 text-sm">Wedding photography - Sarah & Mike</p>
                        </div>
                    </div>
                    <span class="text-white/60 text-sm">2h ago</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-white/10">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-dollar-sign text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-white">Payment received</p>
                            <p class="text-white/60 text-sm">$1,200 for portrait session</p>
                        </div>
                    </div>
                    <span class="text-white/60 text-sm">5h ago</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-images text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-white">Portfolio updated</p>
                            <p class="text-white/60 text-sm">Added 12 new wedding photos</p>
                        </div>
                    </div>
                    <span class="text-white/60 text-sm">1d ago</span>
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
        Alpine.data('dashboardApp', () => ({
            init() {
                // Initialize any data or event listeners here
            }
        }))
    })
</script>
@endpush