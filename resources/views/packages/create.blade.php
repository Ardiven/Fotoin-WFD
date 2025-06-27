@extends('layout.dashboard')

@section('title', isset($package) ? 'Edit Package' : 'Create Package')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-camera mr-3 text-blue-400"></i>
                {{ isset($package) ? 'Edit Package' : 'Create New Package' }}
            </h2>
            <p class="text-white/60 mt-1">{{ isset($package) ? 'Update package details' : 'Add a new photography service package' }}</p>
        </div>
        <a href="{{ route('photographer.packages.index') }}" class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Packages
        </a>
    </div>

    <!-- Form Container -->
    <div class="content-glass border border-white/20 rounded-lg p-6">
        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 px-6 py-4 border rounded-lg bg-red-500/20 border-red-500/40 text-red-300">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mr-3 text-lg mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold mb-2">Please fix the following errors:</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Package Form -->
        <form method="POST" action="{{ isset($package) ? route('photographer.packages.update', $package) : route('photographer.packages.store') }}" id="packageForm">
            @csrf
            @if(isset($package))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <!-- Photographer Profile -->
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <!-- Package Name -->
                <div>
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-tag mr-2 text-blue-400"></i>Package Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="name"
                           value="{{ old('name', isset($package) ? $package->name : '') }}"
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:border-blue-400 transition-colors @error('name') border-red-500 @enderror"
                           placeholder="e.g., Basic Wedding Package"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price and Duration Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Package Price -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-green-400"></i>Price (IDR) <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-white/60">Rp</span>
                            <input type="number" 
                                   name="price"
                                   value="{{ old('price', isset($package) ? $package->price : '') }}"
                                   class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:border-green-400 transition-colors @error('price') border-red-500 @enderror"
                                   placeholder="5000000"
                                   min="0"
                                   step="50000"
                                   required>
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Package Duration -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">
                            <i class="fas fa-clock mr-2 text-purple-400"></i>Duration <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <select name="duration"
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white appearance-none cursor-pointer focus:outline-none focus:border-purple-400 transition-colors @error('duration') border-red-500 @enderror"
                                    style="background-color: rgba(255, 255, 255, 0.05) !important;"
                                    required
                                    onchange="toggleCustomDuration(this)">
                                <option value="" style="background-color: rgba(116, 46, 213, 0.8); color: white;">Select Duration</option>
                                <option value="2" style="background-color: rgba(116, 46, 213, 0.8); color: white;" {{ old('duration', isset($package) && !is_null($package->duration) && in_array($package->duration, [2, 4, 6, 8, 12]) ? $package->duration : '') == '2' ? 'selected' : '' }}>2 Hours</option>
                                <option value="4" style="background-color: rgba(116, 46, 213, 0.8); color: white;" {{ old('duration', isset($package) && !is_null($package->duration) && in_array($package->duration, [2, 4, 6, 8, 12]) ? $package->duration : '') == '4' ? 'selected' : '' }}>4 Hours</option>
                                <option value="6" style="background-color: rgba(116, 46, 213, 0.8); color: white;" {{ old('duration', isset($package) && !is_null($package->duration) && in_array($package->duration, [2, 4, 6, 8, 12]) ? $package->duration : '') == '6' ? 'selected' : '' }}>6 Hours</option>
                                <option value="8" style="background-color: rgba(116, 46, 213, 0.8); color: white;" {{ old('duration', isset($package) && !is_null($package->duration) && in_array($package->duration, [2, 4, 6, 8, 12]) ? $package->duration : '') == '8' ? 'selected' : '' }}>8 Hours (Full Day)</option>
                                <option value="12" style="background-color: rgba(116, 46, 213, 0.8); color: white;" {{ old('duration', isset($package) && !is_null($package->duration) && in_array($package->duration, [2, 4, 6, 8, 12]) ? $package->duration : '') == '12' ? 'selected' : '' }}>12 Hours</option>
                                <option value="custom" style="background-color: rgba(116, 46, 213, 0.8); color: white;" {{ old('duration', isset($package) && !is_null($package->duration) && !in_array($package->duration, [2, 4, 6, 8, 12]) ? 'custom' : '') == 'custom' ? 'selected' : '' }}>Custom Duration</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-white/40 pointer-events-none"></i>
                        </div>
                        @error('duration')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- Custom Duration Input -->
                <div id="customDurationDiv" style="display: {{ old('duration', isset($package) && !is_null($package->duration) && !in_array($package->duration, [2, 4, 6, 8, 12]) ? 'custom' : '') == 'custom' ? 'block' : 'none' }};">
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-stopwatch mr-2 text-yellow-400"></i>Custom Duration (Hours)
                    </label>
                    <input type="number" 
                           name="custom_duration"
                           value="{{ old('custom_duration', isset($package) && !is_null($package->duration) && !in_array($package->duration, [2, 4, 6, 8, 12]) ? $package->duration : '') }}"
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:border-yellow-400 transition-colors @error('custom_duration') border-red-500 @enderror"
                           placeholder="Enter hours"
                           min="1"
                           max="24">
                    @error('custom_duration')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Package Description -->
                <div>
                    <label class="block text-white text-sm font-medium mb-2">
                        <i class="fas fa-align-left mr-2 text-blue-400"></i>Description
                    </label>
                    <textarea name="description"
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white placeholder-white/40 h-24 resize-none focus:outline-none focus:border-blue-400 transition-colors @error('description') border-red-500 @enderror"
                              placeholder="Brief description of what's included in this package...">{{ old('description', isset($package) ? $package->description : '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Features Section -->
                <div>
                    <label class="block text-white text-sm font-medium mb-3">
                        <i class="fas fa-list-check mr-2 text-purple-400"></i>Features Included
                    </label>
                    
                    <!-- Feature List -->
                    <div class="stats-card border border-white/20 rounded-lg p-4">
                        <div id="features-list" class="space-y-3">
                            @php
                                $existingFeatures = [];
                                if (isset($package) && $package->features) {
                                    $existingFeatures = is_string($package->features) 
                                        ? json_decode($package->features, true) 
                                        : $package->features;
                                }
                                // Handle old input for features
                                if (old('features')) {
                                    $existingFeatures = old('features');
                                }
                                // Ensure we have at least one empty feature field
                                if (empty($existingFeatures)) {
                                    $existingFeatures = [''];
                                }
                            @endphp
                            
                            @foreach($existingFeatures as $index => $feature)
                                <div class="feature-item flex items-center gap-3">
                                    <input type="text" 
                                           name="features[]" 
                                           value="{{ $feature->name?? '' }}"
                                           class="flex-1 px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white placeholder-white/40 text-sm focus:outline-none focus:border-blue-400 transition-colors"
                                           placeholder="Enter feature description">
                                    <button type="button" onclick="removeFeature(this)" class="text-red-400 hover:text-red-300 transition-colors p-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" onclick="addFeature()" class="mt-3 text-blue-400 hover:text-blue-300 transition-colors text-sm flex items-center">
                            <i class="fas fa-plus mr-2"></i>Add Feature
                        </button>
                    </div>
                    @error('features')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Package Status -->
                <div>
                    <label class="block text-white text-sm font-medium mb-3">
                        <i class="fas fa-toggle-on mr-2 text-green-400"></i>Package Status
                    </label>
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', isset($package) ? $package->is_active : '1') == '1' ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="relative w-5 h-5 bg-white/10 border-2 border-white/30 rounded-full peer-checked:bg-green-500 peer-checked:border-green-500 transition-all">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                            </div>
                            <span class="ml-3 text-white text-sm">Active</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" 
                                   name="is_active" 
                                   value="0" 
                                   {{ old('is_active', isset($package) ? $package->is_active : '1') == '0' ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="relative w-5 h-5 bg-white/10 border-2 border-white/30 rounded-full peer-checked:bg-red-500 peer-checked:border-red-500 transition-all">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fas fa-times text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                            </div>
                            <span class="ml-3 text-white text-sm">Inactive</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-white/20">
                <a href="{{ route('photographer.packages.index') }}" class="glass-effect border border-white/20 text-white px-6 py-2 rounded-lg hover:bg-white/10 transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    {{ isset($package) ? 'Update Package' : 'Create Package' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleCustomDuration(select) {
    const customDiv = document.getElementById('customDurationDiv');
    const customInput = document.querySelector('input[name="custom_duration"]');
    
    if (select.value === 'custom') {
        customDiv.style.display = 'block';
        customInput.required = true;
    } else {
        customDiv.style.display = 'none';
        customInput.required = false;
        customInput.value = '';
    }
}

function addFeature() {
    const featuresList = document.getElementById('features-list');
    const newFeature = document.createElement('div');
    newFeature.className = 'feature-item flex items-center gap-3';
    newFeature.innerHTML = `
        <input type="text" 
               name="features[]" 
               class="flex-1 px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white placeholder-white/40 text-sm focus:outline-none focus:border-blue-400 transition-colors"
               placeholder="Enter feature description">
        <button type="button" onclick="removeFeature(this)" class="text-red-400 hover:text-red-300 transition-colors p-1">
            <i class="fas fa-trash-alt"></i>
        </button>
    `;
    featuresList.appendChild(newFeature);
}

function removeFeature(button) {
    const featuresList = document.getElementById('features-list');
    if (featuresList.children.length > 1) {
        button.closest('.feature-item').remove();
    }
}

// Initialize custom duration visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    const durationSelect = document.querySelector('select[name="duration"]');
    if (durationSelect) {
        toggleCustomDuration(durationSelect);
    }
});
</script>
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

    /* Custom select dropdown styling */
    select option {
        background: #1e293b;
        color: white;
    }
</style>
@endpush