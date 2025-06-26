@extends('layouts.admin')

@section('title', 'Create New Specialty')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-white">Create New Specialty</h2>
            <p class="text-white/70 mt-1">Add a new photography specialty to your platform</p>
        </div>
        <a href="{{ route('admin.specialties.index') }}" 
           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    <!-- Form -->
    <div class="glass-effect border border-white/20 rounded-xl p-8">
        <form action="{{ route('admin.specialties.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-white font-medium mb-2">
                    Specialty Name <span class="text-red-400">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-transparent"
                       placeholder="e.g., Wedding Photography, Portrait, Nature"
                       required>
                @error('name')
                    <p class="mt-2 text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" class="block text-white font-medium mb-2">
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-transparent"
                          placeholder="Describe this photography specialty...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Icon Field -->
            <div x-data="{ selectedIcon: '{{ old('icon', 'fas fa-camera') }}' }">
                <label for="icon" class="block text-white font-medium mb-2">
                    Icon (FontAwesome Class)
                </label>
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <input type="text" 
                               id="icon" 
                               name="icon" 
                               x-model="selectedIcon"
                               value="{{ old('icon') }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-transparent"
                               placeholder="e.g., fas fa-camera, fas fa-heart, fas fa-leaf">
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-lg flex items-center justify-center">
                        <i :class="selectedIcon || 'fas fa-question'" class="text-white text-2xl"></i>
                    </div>
                </div>
                @error('icon')
                    <p class="mt-2 text-red-400 text-sm">{{ $message }}</p>
                @enderror
                
                <!-- Popular Icons -->
                <div class="mt-4">
                    <p class="text-white/70 text-sm mb-3">Popular icons:</p>
                    <div class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-10 gap-2">
                        @php
                            $icons = [
                                'fas fa-camera', 'fas fa-heart', 'fas fa-leaf', 'fas fa-baby',
                                'fas fa-ring', 'fas fa-birthday-cake', 'fas fa-mountain',
                                'fas fa-building', 'fas fa-user-tie', 'fas fa-palette',
                                'fas fa-car', 'fas fa-plane', 'fas fa-utensils', 'fas fa-music',
                                'fas fa-paw', 'fas fa-home', 'fas fa-shopping-bag', 'fas fa-graduation-cap'
                            ];
                        @endphp
                        @foreach($icons as $icon)
                            <button type="button" 
                                    @click="selectedIcon = '{{ $icon }}'"
                                    class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-colors"
                                    :class="selectedIcon === '{{ $icon }}' ? 'bg-white/30' : ''">
                                <i class="{{ $icon }} text-white"></i>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sort Order Field -->
            <div>
                <label for="sort_order" class="block text-white font-medium mb-2">
                    Sort Order
                </label>
                <input type="number" 
                       id="sort_order" 
                       name="sort_order" 
                       value="{{ old('sort_order', 0) }}"
                       min="0"
                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-transparent"
                       placeholder="0">
                <p class="mt-1 text-white/50 text-sm">Lower numbers appear first</p>
                @error('sort_order')
                    <p class="mt-2 text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="flex items-center">
                <input type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-5 h-5 bg-white/10 border-white/20 rounded text-white/80 focus:ring-white/30">
                <label for="is_active" class="ml-3 text-white font-medium">
                    Active
                </label>
                <p class="ml-2 text-white/50 text-sm">(Visible to users)</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-white/20">
                <a href="{{ route('admin.specialties.index') }}" 
                   class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Create Specialty
                </button>
            </div>
        </form>
    </div>
</div>
@endsection