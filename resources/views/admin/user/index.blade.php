@extends('layout.admin')

@section('title', 'FotoIn Dashboard - User Management')

@section('page-header')
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-white">User Management</h2>
        <a href="{{ route('admin.users.create') }}" class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
            <i class="fas fa-plus mr-2"></i>Add New User
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500/50 text-green-100 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500/50 text-red-100 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-white/60 text-sm mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name or email..."
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-white/60 text-sm mb-2">Role</label>
                    <select name="role" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-400">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="photographer" {{ request('role') == 'photographer' ? 'selected' : '' }}>Photographer</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div>
                    <label class="block text-white/60 text-sm mb-2">Status</label>
                    <select name="status" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-400">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="content-glass border border-white/20 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="text-left text-white/60 px-6 py-4 text-sm font-medium">User</th>
                            <th class="text-left text-white/60 px-6 py-4 text-sm font-medium">Email</th>
                            <th class="text-left text-white/60 px-6 py-4 text-sm font-medium">Role</th>
                            <th class="text-left text-white/60 px-6 py-4 text-sm font-medium">Status</th>
                            <th class="text-left text-white/60 px-6 py-4 text-sm font-medium">Joined</th>
                            <th class="text-left text-white/60 px-6 py-4 text-sm font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($users as $user)
                            <tr class="hover:bg-white/5">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-white font-medium">{{ $user->name }}</p>
                                            @if($user->phone)
                                                <p class="text-white/60 text-sm">{{ $user->phone }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-white">{{ $user->email }}</p>
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">
                                            <i class="fas fa-check-circle mr-1"></i>Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-400">
                                            <i class="fas fa-exclamation-circle mr-1"></i>Unverified
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                        @if($user->role == 'admin') bg-red-500/20 text-red-400
                                        @elseif($user->role == 'photographer') bg-blue-500/20 text-blue-400
                                        @else bg-gray-500/20 text-gray-400 @endif">
                                        @if($user->hasRole('admin'))
                                            <i class="fas fa-shield-alt mr-1"></i>
                                        @elseif($user->hasRole('customer'))
                                            <i class="fas fa-camera mr-1"></i>
                                        @else
                                            <i class="fas fa-user mr-1"></i>
                                        @endif
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">
                                            <i class="fas fa-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-500/20 text-red-400">
                                            <i class="fas fa-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-white/60">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="text-blue-400 hover:text-blue-300" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="text-yellow-400 hover:text-yellow-300" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="#" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="{{ $user->is_active ? 'text-red-400 hover:text-red-300' : 'text-green-400 hover:text-green-300' }}" 
                                                    title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-white/60">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p>No users found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-white/10">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
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
</style>
@endpush