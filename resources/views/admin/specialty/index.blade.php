@extends('layout.admin')

@section('title', 'Specialties Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-white">Specialties Management</h2>
            <p class="text-white/70 mt-1">Manage photography specialties and categories</p>
        </div>
        <a href="{{ route('admin.specialties.create') }}" 
           class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Add New Specialty
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="glass-effect border border-green-400/50 bg-green-400/10 text-white px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-400"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Specialties Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($specialties as $specialty)
            <div class="glass-effect border border-white/20 rounded-xl p-6 hover:bg-white/10 transition-all duration-200">
                <!-- Specialty Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        @if($specialty->icon)
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="{{ $specialty->icon }} text-white text-xl"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-white font-semibold text-lg">{{ $specialty->name }}</h3>
                            <div class="flex items-center mt-1">
                                <span class="text-xs px-2 py-1 rounded-full {{ $specialty->is_active ? 'bg-green-500/20 text-green-300' : 'bg-red-500/20 text-red-300' }}">
                                    {{ $specialty->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-white/50 text-xs ml-2">Order: {{ $specialty->sort_order }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Toggle -->
                    <form action="#" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="text-white/60 hover:text-white transition-colors p-2"
                                title="{{ $specialty->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="fas {{ $specialty->is_active ? 'fa-toggle-on text-green-400' : 'fa-toggle-off' }}"></i>
                        </button>
                    </form>
                </div>

                <!-- Description -->
                @if($specialty->description)
                    <p class="text-white/70 text-sm mb-4 line-clamp-3">{{ $specialty->description }}</p>
                @else
                    <p class="text-white/50 text-sm mb-4 italic">No description available</p>
                @endif

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-white/10">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.specialties.show', $specialty->id) }}" 
                           class="text-blue-400 hover:text-blue-300 transition-colors"
                           title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.specialties.edit', $specialty->id) }}" 
                           class="text-yellow-400 hover:text-yellow-300 transition-colors"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    
                    <form action="{{ route('admin.specialties.destroy', $specialty->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this specialty?')"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-400 hover:text-red-300 transition-colors"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>

                <!-- Metadata -->
                <div class="mt-4 pt-3 border-t border-white/10 text-xs text-white/50">
                    <div class="flex justify-between">
                        <span>Created: {{ \Carbon\Carbon::parse($specialty->created_at)->format('M d, Y') }}</span>
                        <span>Updated: {{ \Carbon\Carbon::parse($specialty->updated_at)->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="glass-effect border border-white/20 rounded-xl p-12 text-center">
                    <i class="fas fa-camera text-white/30 text-6xl mb-4"></i>
                    <h3 class="text-white text-xl font-semibold mb-2">No Specialties Found</h3>
                    <p class="text-white/70 mb-6">Get started by creating your first photography specialty.</p>
                    <a href="{{ route('admin.specialties.create') }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Add First Specialty
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
        <div class="stats-card rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-white mb-2">{{ $specialties->count() }}</div>
            <div class="text-white/70">Total Specialties</div>
        </div>
        <div class="stats-card rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-green-400 mb-2">{{ $specialties->where('is_active', 1)->count() }}</div>
            <div class="text-white/70">Active</div>
        </div>
        <div class="stats-card rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-red-400 mb-2">{{ $specialties->where('is_active', 0)->count() }}</div>
            <div class="text-white/70">Inactive</div>
        </div>
        <div class="stats-card rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-blue-400 mb-2">{{ $specialties->where('description', '!=', null)->count() }}</div>
            <div class="text-white/70">With Description</div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection