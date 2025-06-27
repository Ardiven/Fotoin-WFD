@extends('layout.admin')

@section('title', 'Specialty Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-white">Specialty Details</h2>
            <p class="text-white/70 mt-1">View specialty information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.specialties.edit', $specialty->id) }}" 
               class="bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-300 px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.specialties.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info Card -->
        <div class="lg:col-span-2">
            <div class="glass-effect border border-white/20 rounded-xl p-8 space-y-6">
                <!-- Specialty Header -->
                <div class="flex items-start space-x-6">
                    @if($specialty->icon)
                        <div class="w-20 h-20 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="{{ $specialty->icon }} text-white text-3xl"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $specialty->name }}</h3>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 rounded-full text-sm {{ $specialty->is_active ? 'bg-green-500/20 text-green-300' : 'bg-red-500/20 text-red-300' }}">
                                {{ $specialty->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="text-white/60 text-sm">Sort Order: {{ $specialty->sort_order }}</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h4 class="text-white font-semibold mb-3">Description</h4>
                    @if($specialty->description)
                        <p class="text-white/80 leading-relaxed">{{ $specialty->description }}</p>
                    @else
                        <p class="text-white/50 italic">No description provided for this specialty.</p>
                    @endif
                </div>

                <!-- Icon Information -->
                <div>
                    <h4 class="text-white font-semibold mb-3">Icon Information</h4>
                    <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                        @if($specialty->icon)
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="{{ $specialty->icon }} text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $specialty->icon }}</p>
                                    <p class="text-white/60 text-sm">FontAwesome class</p>
                                </div>
                            </div>
                        @else
                            <p class="text-white/50 italic">No icon assigned to this specialty.</p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-4 pt-6 border-t border-white/20">
                    <a href="{{ route('admin.specialties.edit', $specialty->id) }}" 
                       class="px-6 py-3 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-300 rounded-lg transition-all duration-200 flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Specialty
                    </a>
                    
                    <form action="{{ route('admin.specialties.toggle-status', $specialty->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="px-6 py-3 {{ $specialty->is_active ? 'bg-red-500/20 hover:bg-red-500/30 text-red-300' : 'bg-green-500/20 hover:bg-green-500/30 text-green-300' }} rounded-lg transition-all duration-200 flex items-center">
                            <i class="fas {{ $specialty->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                            {{ $specialty->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.specialties.destroy', $specialty->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this specialty? This action cannot be undone.')"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-6 py-3 bg-red-500/20 hover:bg-red-500/30 text-red-300 rounded-lg transition-all duration-200 flex items-center">
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="glass-effect border border-white/20 rounded-xl p-6">
                <h4 class="text-white font-semibold mb-4">Quick Information</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-white/70">ID</span>
                        <span class="text-white font-medium">#{{ $specialty->id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70">Status</span>
                        <span class="px-2 py-1 rounded text-xs {{ $specialty->is_active ? 'bg-green-500/20 text-green-300' : 'bg-red-500/20 text-red-300' }}">
                            {{ $specialty->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70">Sort Order</span>
                        <span class="text-white font-medium">{{ $specialty->sort_order }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70">Has Icon</span>
                        <span class="text-white font-medium">{{ $specialty->icon ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70">Has Description</span>
                        <span class="text-white font-medium">{{ $specialty->description ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="glass-effect border border-white/20 rounded-xl p-6">
                <h4 class="text-white font-semibold mb-4">Timeline</h4>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-3 h-3 bg-green-400 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-white font-medium">Created</p>
                            <p class="text-white/60 text-sm">{{ \Carbon\Carbon::parse($specialty->created_at)->format('M d, Y') }}</p>
                            <p class="text-white/50 text-xs">{{ \Carbon\Carbon::parse($specialty->created_at)->format('H:i') }}</p>
                        </div>
                    </div>
                    @if($specialty->updated_at != $specialty->created_at)
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 bg-blue-400 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-white font-medium">Last Updated</p>
                                <p class="text-white/60 text-sm">{{ \Carbon\Carbon::parse($specialty->updated_at)->format('M d, Y') }}</p>
                                <p class="text-white/50 text-xs">{{ \Carbon\Carbon::parse($specialty->updated_at)->format('H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usage Stats (Placeholder for future implementation) -->
            <div class="glass-effect border border-white/20 rounded-xl p-6">
                <h4 class="text-white font-semibold mb-4">Usage Statistics</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-white/70">Photographers</span>
                        <span class="text-white font-medium">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70">Bookings</span>
                        <span class="text-white font-medium">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70">Views</span>
                        <span class="text-white font-medium">-</span>
                    </div>
                </div>
                <p class="text-white/50 text-xs mt-3 italic">Statistics will be available when integrated with photographer profiles</p>
            </div>
        </div>
    </div>
</div>
@endsection