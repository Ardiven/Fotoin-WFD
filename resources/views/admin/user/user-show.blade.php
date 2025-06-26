@extends('layout.admin')

@section('title', 'FotoIn Dashboard - View User')

@section('page-header')
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.users.index') }}" class="text-white/60 hover:text-white mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-white">User Details</h2>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
                <i class="fas fa-edit mr-2"></i>Edit User
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500/50 text-green-100 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- User Profile Card -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <div class="flex items-start space-x-6">
                <!-- Avatar -->
                <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-3xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                
                <!-- User Info -->
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-2">
                        <h3 class="text-2xl font-bold text-white">{{ $user->name }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm
                            @if($user->role == 'admin') bg-red-500/20 text-red-400
                            @elseif($user->role == 'photographer') bg-blue-500/20 text-blue-400
                            @else bg-gray-500/20 text-gray-400 @endif">
                            @if($user->role == 'admin')
                                <i class="fas fa-shield-alt mr-1"></i>
                            @elseif($user->role == 'photographer')
                                <i class="fas fa-camera mr-1"></i>
                            @else
                                <i class="fas fa-user mr-1"></i>
                            @endif
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-500/20 text-green-400">
                                <i class="fas fa-circle mr-1"></i>Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-500/20 text-red-400">
                                <i class="fas fa-circle mr-1"></i>Inactive
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-white/60 mb-2">{{ $user->email }}</p>
                    
                    @if($user->phone)
                        <p class="text-white/60 mb-2">
                            <i class="fas fa-phone mr-2"></i>{{ $user->phone }}
                        </p>
                    @endif
                    
                    <div class="flex items-center space-x-6 text-sm text-white/60">
                        <span>
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Joined {{ $user->created_at->format('M d, Y') }}
                        </span>
                        @if($user->email_verified_at)
                            <span class="text-green-400">
                                <i class="fas fa-check-circle mr-1"></i>
                                Email Verified
                            </span>
                        @else
                            <span class="text-yellow-400">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Email Not Verified
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-6">Account Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white/60 text-sm font-medium mb-1">User ID</label>
                    <p class="text-white">{{ $user->id }}</p>
                </div>
                
                <div>
                    <label class="block text-white/60 text-sm font-medium mb-1">Full Name</label>
                    <p class="text-white">{{ $user->name }}</p>
                </div>
                
                <div>
                    <label class="block text-white/60 text-sm font-medium mb-1">Email Address</label>
                    <p class="text-white">{{ $user->email }}</p>
                </div>
                
                <div>
                    <label class="block text-white/60 text-sm font-medium mb-1">Phone Number</label>
                    <p class="text-white">{{ $user->phone ?: 'Not provided' }}</p>
                </div>
                
                <div>
                    <label class="block text-white/60 text-sm font-medium mb-1">Role</label>
                    <p class="text-white">{{ ucfirst($user->role) }}</p>
                </div>
                
                <div>
                    <label class="block text-white/60 text-sm font-medium mb-1">Status</label>
                    <p class="text-white">{{ $user->is_active ? 'Active' : 'Inactive' }}</p>
                </div>
                
                <div>
                    <label class="block text-white/60 text-sm font-medium mb-1">Created At</label>
                    <p class="text-white">{{ $user->created_at->format('M d, Y H:i:s') }}</p>
                </div>
                
                <div>
                    <label class="block text-white/60 text-sm font-medium mb-1">Last Updated</label>
                    <p class="text-white">{{ $user->updated_at->format('M d, Y H:i:s') }}</p>
                </div>
                
                <div>
                    <label class="block text-white/60 text-sm font-medium mb-1">Email Verified At</label>
                    <p class="text-white">
                        {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i:s') : 'Not verified' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Account Actions -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-6">Account Actions</h3>
            
            <div class="flex flex-wrap gap-4">
                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} mr-2"></i>
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                    </button>
                </form>
                
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
                
                @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-trash mr-2"></i>Delete User
                        </button>
                    </form>
                @else
                    <button disabled 
                            class="bg-gray-500 text-gray-300 px-4 py-2 rounded-lg cursor-not-allowed">
                        <i class="fas fa-trash mr-2"></i>Cannot Delete Self
                    </button>
                @endif
            </div>
        </div>

        @if($user->role == 'photographer')
        <!-- Photographer Stats (if applicable) -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-6">Photographer Statistics</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-400">0</div>
                    <div class="text-white/60 text-sm">Total Bookings</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-400">Rp 0</div>
                    <div class="text-white/60 text-sm">Total Earnings</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-400">0.0</div>
                    <div class="text-white/60 text-sm">Average Rating</div>
                </div>
            </div>
        </div>
        @endif
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
</style>
@endpush