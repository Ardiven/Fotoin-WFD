@extends('layout.app')

@section('title', 'Find Professional Photographers - FotoIn')

@push('styles')
<style>
    .bg-gradient-auth {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
    .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .glass-dark {
        backdrop-filter: blur(10px);
        background: rgba(0, 0, 0, 0.2);
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    }
    .filter-active {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }
    .text-glow {
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .filter-dropdown {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    .filter-dropdown.active {
        max-height: 400px;
    }
    .photographer-hidden {
        display: none;
    }
    .loading-spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 4px solid white;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .error-message {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: white;
        padding: 1rem;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }
    .price-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.5rem;
        border-radius: 0.5rem;
        width: 100%;
    }
    .price-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    .price-input:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.5);
        background: rgba(255, 255, 255, 0.15);
    }
    .apply-filter-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        transition: all 0.3s ease;
    }
    .apply-filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }
    .clear-filter-btn {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.3);
        transition: all 0.3s ease;
    }
    .clear-filter-btn:hover {
        background: rgba(239, 68, 68, 0.3);
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-auth">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;><circle cx=&quot;7&quot; cy=&quot;7&quot; r=&quot;1&quot;/><circle cx=&quot;27&quot; cy=&quot;7&quot; r=&quot;1&quot;/><circle cx=&quot;47&quot; cy=&quot;7&quot; r=&quot;1&quot;/><circle cx=&quot;7&quot; cy=&quot;27&quot; r=&quot;1&quot;/><circle cx=&quot;27&quot; cy=&quot;27&quot; r=&quot;1&quot;/><circle cx=&quot;47&quot; cy=&quot;27&quot; r=&quot;1&quot;/><circle cx=&quot;7&quot; cy=&quot;47&quot; r=&quot;1&quot;/><circle cx=&quot;27&quot; cy=&quot;47&quot; r=&quot;1&quot;/><circle cx=&quot;47&quot; cy=&quot;47&quot; r=&quot;1&quot;/></g></g></svg>')"></div>
    </div>

    <!-- Header -->
    <div class="relative pt-8 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 text-glow animate-float">
                    {{ __('Find Professional Photographers') }}
                </h1>
                <p class="text-lg text-white/80 max-w-2xl mx-auto">
                    {{ __('Discover and book the perfect photographer for your special moments') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="relative pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="glass-effect rounded-2xl p-6 mb-6">
                <form id="searchForm" method="GET" action="#">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-white/60"></i>
                            <input type="text" 
                                   id="searchInput" 
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('Search photographers by name or specialty...') }}" 
                                   class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:border-white/50 backdrop-blur-sm">
                        </div>
                        <button id="locationBtn" 
                                type="button"
                                class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ __('Near Me') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Filter Tabs -->
            <div class="glass-effect rounded-2xl p-6 mb-8">
                <div class="flex flex-wrap gap-4 mb-6">
                    <button class="filter-tab px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30" data-filter="category">
                        <i class="fas fa-th-large mr-2"></i>{{ __('Category') }}
                    </button>
                    <button class="filter-tab px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30" data-filter="location">
                        <i class="fas fa-map-marker-alt mr-2"></i>{{ __('Location') }}
                    </button>
                    <button class="filter-tab px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30" data-filter="price">
                        <i class="fas fa-dollar-sign mr-2"></i>{{ __('Price Range') }}
                    </button>
                    <button id="applyFiltersBtn" class="apply-filter-btn px-3 py-3 text-white font-semibold rounded-lg shadow-lg">
                        <i class="fas fa-filter mr-2"></i>
                        {{ __('Apply Filters') }}
                    </button>
                    <button id="clearFiltersBtn" class="clear-filter-btn px-3 py-3 text-white font-semibold rounded-lg">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('Clear Filters') }}
                    </button>
                </div>

                <!-- Filter Dropdowns -->
                <div id="categoryFilter" class="filter-dropdown mb-4">
                    <div id="categoryOptions" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($specialties as $specialty)
                        <label class="flex items-center text-white cursor-pointer">
                            <input type="checkbox" 
                                   class="category-checkbox mr-2" 
                                   value="{{ $specialty->name }}"
                                   {{ in_array($specialty->name, request('specialties', [])) ? 'checked' : '' }}>
                            <span class="capitalize">{{ __($specialty->name) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div id="locationFilter" class="filter-dropdown mb-4">
                    <div id="locationOptions" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($cities as $location)
                        <label class="flex items-center text-white cursor-pointer">
                            <input type="checkbox" 
                                   class="location-checkbox mr-2" 
                                   value="{{ $location->name }}"
                                   {{ in_array($location->name, request('locations', [])) ? 'checked' : '' }}>
                            <span class="capitalize">{{ __($location->name) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div id="priceFilter" class="filter-dropdown mb-4">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-white mb-2">
                                    {{ __('Minimum Price (Rp)') }}
                                </label>
                                <input type="number" 
                                       id="minPriceInput" 
                                       name="min_price"
                                       class="price-input" 
                                       min="0" 
                                       value="{{ request('min_price', '') }}"
                                       placeholder="e.g. 500000">
                            </div>
                            <div>
                                <label class="block text-white mb-2">
                                    {{ __('Maximum Price (Rp)') }}
                                </label>
                                <input type="number" 
                                       id="maxPriceInput" 
                                       name="max_price"
                                       class="price-input" 
                                       min="0" 
                                       value="{{ request('max_price', '') }}"
                                       placeholder="e.g. 5000000">
                            </div>
                        </div>
                        <div class="text-sm text-white/70">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ __('Leave empty for no limit') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <div class="text-white mb-4 md:mb-0">
                    <span id="resultCount">{{ $photographers->count() }}</span> {{ __('photographers found') }}
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-white">{{ __('Sort by') }}:</span>
                    <select id="sortSelect" 
                            name="sort"
                            class="bg-white/50 border border-white/30 text-dark rounded-lg px-4 py-2 focus:outline-none focus:border-white/50 backdrop-blur-sm">
                        <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>{{ __('Recommended') }}</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('Highest Rated') }}</option>
                        <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                        <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center py-8" style="display: none;">
        <div class="loading-spinner"></div>
        <p class="text-white mt-4">{{ __('Loading photographers...') }}</p>
    </div>

    <!-- Error Message -->
    @if($errors->any() || session('error'))
    <div id="errorMessage" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="error-message">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span id="errorText">
                @if($errors->any())
                    {{ $errors->first() }}
                @else
                    {{ session('error') }}
                @endif
            </span>
        </div>
    </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-green-500/20 border border-green-500/30 text-white p-4 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Photographers Grid -->
    <div class="relative pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="photographersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($photographers as $photographer)
                    <div class="glass-effect rounded-2xl overflow-hidden card-hover transition-all duration-300 photographer-card" 
                        data-categories="{{ $photographer->specialties->pluck('slug')->implode(',') }}"
                        data-locations="{{ $photographer->cities->pluck('slug')->implode(',') }}"
                        data-price="{{ $photographer->packages()->orderBy('price', 'asc')->first()?->price ?? 0 }}"
                        data-rating="{{ $photographer->rating ?? 0 }}">
                        <div class="relative">
                            @if($photographer->profile_photo)
                            <img src="{{ Storage::url($photographer->profile_photo) }}" 
                                alt="{{ $photographer->name }}" 
                                class="w-full h-48 object-cover">
                            @else
                            <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-camera text-4xl text-white/80 mb-2"></i>
                                    <p class="text-white/60 text-sm">{{ $photographer->name }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($photographer->is_available)
                            <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                {{ __('Available') }}
                            </div>
                            @endif
                            
                            @if($photographer->instant_booking)
                            <div class="absolute top-4 left-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                <i class="fas fa-bolt mr-1"></i>{{ __('Instant') }}
                            </div>
                            @endif

                            @if($photographer->weekend_available)
                            <div class="absolute bottom-4 left-4 bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-semibold shadow-lg">
                                {{ __('Weekend') }}
                            </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-white mb-1 truncate">{{ $photographer->name }}</h3>
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($photographer->specialties->take(2) as $specialty)
                                        <span class="text-white/70 text-sm bg-white/10 px-2 py-1 rounded-full">
                                            {{ $specialty->name }}
                                        </span>
                                        @endforeach
                                        @if($photographer->specialties->count() > 2)
                                        <span class="text-white/50 text-sm">+{{ $photographer->specialties->count() - 2 }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($photographer->rating)
                                <div class="flex items-center bg-yellow-500/20 px-2 py-1 rounded-lg ml-4">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="text-white font-semibold">{{ number_format($photographer->rating, 1) }}</span>
                                    @if($photographer->reviews_count)
                                    <span class="text-white/70 text-sm ml-1">({{ $photographer->reviews_count }})</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                            
                            @if($photographer->cities->count() > 0)
                            <div class="flex items-center text-white/70 text-sm mb-3">
                                <i class="fas fa-map-marker-alt mr-2 text-white/50"></i>
                                {{ $photographer->cities->pluck('name')->implode(', ') }}
                            </div>
                            @endif
                            
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-white">
                                    <span class="text-sm text-white/70">{{ __('Starting from') }}</span>
                                    @php
                                        $minPrice = $photographer->packages()->orderBy('price', 'asc')->first();
                                    @endphp
                                    @if($minPrice)
                                    <div class="text-xl font-bold">Rp {{ number_format($minPrice->price, 0, ',', '.') }}</div>
                                    @else
                                    <div class="text-lg text-white/70">{{ __('Contact for Price') }}</div>
                                    @endif
                                </div>
                                
                                <div class="flex items-center space-x-1">
                                    @if($photographer->packages_count)
                                    <span class="bg-white/20 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $photographer->packages_count }} {{ __('packages') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex space-x-3">
                                <a href="{{ route('customer.photographers.show', $photographer) }}" 
                                class="flex-1 bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 text-center backdrop-blur-sm border border-white/20 hover:border-white/40">
                                    {{ __('View Profile') }}
                                </a>
                                @if($photographer->instant_booking)
                                <a href="{{ route('customer.booking.create', $photographer) }}" 
                                class="flex-1 bg-gradient-secondary hover:opacity-90 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 text-center shadow-lg hover:shadow-xl">
                                    {{ __('Book Now') }}
                                </a>
                                @else
                                <a href="{{ route('customer.photographers.show', $photographer) }}#contact" 
                                class="flex-1 bg-gradient-secondary hover:opacity-90 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 text-center shadow-lg hover:shadow-xl">
                                    {{ __('Contact') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="glass-effect rounded-2xl p-8">
                            <i class="fas fa-search text-6xl text-white/50 mb-4"></i>
                            <h3 class="text-2xl font-bold text-white mb-2">{{ __('No photographers found') }}</h3>
                            <p class="text-white/70 mb-6">{{ __('Try adjusting your search criteria or browse all photographers.') }}</p>
                            <button onclick="clearAllFilters()" 
                                    class="inline-block bg-white/20 hover:bg-white/30 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30">
                                {{ __('Clear Filters') }}
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            {{-- @if($photographers->hasPages())
            <div class="mt-12 flex justify-center">
                <div class="glass-effect rounded-2xl p-4">
                    {{ $photographers->appends(request()->query())->links('pagination.custom') }}
                </div>
            </div>
            @endif --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fixed JavaScript for Photographer Finder Filters
document.addEventListener('DOMContentLoaded', function() {
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const filterTabs = document.querySelectorAll('.filter-tab');
    const sortSelect = document.getElementById('sortSelect');
    const minPriceInput = document.getElementById('minPriceInput');
    const maxPriceInput = document.getElementById('maxPriceInput');
    const locationBtn = document.getElementById('locationBtn');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const photographersGrid = document.getElementById('photographersGrid');
    const resultCount = document.getElementById('resultCount');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    
    // Initialize
    setupEventListeners();
    
    // Global variable to track current request
    let currentRequest = null;
    
    function setupEventListeners() {
        // Search input with debounce (auto-search as you type)
        searchInput.addEventListener('input', debounce(handleFilterChange, 500));
        
        // Filter tabs
        filterTabs.forEach(tab => {
            tab.addEventListener('click', toggleFilterDropdown);
        });
        
        // Sort select (auto-apply)
        sortSelect.addEventListener('change', handleFilterChange);
        
        // Location button
        locationBtn.addEventListener('click', handleLocationSearch);
        
        // Apply filters button
        applyFiltersBtn.addEventListener('click', handleFilterChange);
        
        // Clear filters button
        clearFiltersBtn.addEventListener('click', clearAllFilters);
        
        // Format price inputs
        minPriceInput.addEventListener('input', formatPriceInput);
        maxPriceInput.addEventListener('input', formatPriceInput);
        
        // Add change listeners for filter inputs
        document.querySelectorAll('.category-checkbox, .location-checkbox, .availability-checkbox, .rating-radio').forEach(input => {
            input.addEventListener('change', debounce(handleFilterChange, 300));
        });
    }
    
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    function formatPriceInput(e) {
        // Remove non-numeric characters except for digits
        let value = e.target.value.replace(/[^\d]/g, '');
        e.target.value = value;
    }
    
    function toggleFilterDropdown(e) {
        const filterType = e.target.dataset.filter || e.target.closest('.filter-tab').dataset.filter;
        const dropdown = document.getElementById(filterType + 'Filter');
        
        // Close all other dropdowns
        document.querySelectorAll('.filter-dropdown').forEach(d => {
            if (d !== dropdown) {
                d.classList.remove('active');
            }
        });
        
        // Remove active class from all tabs
        filterTabs.forEach(tab => tab.classList.remove('filter-active'));
        
        // Toggle current dropdown
        dropdown.classList.toggle('active');
        
        // Update tab appearance
        if (dropdown.classList.contains('active')) {
            e.target.closest('.filter-tab').classList.add('filter-active');
        }
    }

    function handleLocationSearch() {
        if (!navigator.geolocation) {
            showError('Geolocation is not supported by this browser.');
            return;
        }
        
        locationBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Getting location...';
        locationBtn.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                
                // Add location parameters to current filters and search
                const params = new URLSearchParams(window.location.search);
                params.set('lat', latitude);
                params.set('lng', longitude);
                
                // Update URL and reload with location
                window.location.href = window.location.pathname + '?' + params.toString();
            },
            function(error) {
                console.error('Geolocation error:', error);
                showError('Unable to get your location. Please try again.');
                locationBtn.innerHTML = '<i class="fas fa-map-marker-alt mr-2"></i>Near Me';
                locationBtn.disabled = false;
            }
        );
    }

    function handleFilterChange() {
        // Cancel previous request if it exists
        if (currentRequest) {
            currentRequest.abort();
        }
        
        showLoading(true);
        
        const formData = collectFilterData();
        
        // Create URL with parameters
        const params = new URLSearchParams();
        Object.entries(formData).forEach(([key, value]) => {
            if (value !== '' && value !== null && value !== undefined) {
                if (Array.isArray(value)) {
                    value.forEach(v => {
                        if (v !== '') {
                            params.append(key + '[]', v);
                        }
                    });
                } else {
                    params.set(key, value);
                }
            }
        });
        
        // Update URL without page reload
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({}, '', newUrl);
        
        // Make AJAX request
        currentRequest = fetch(newUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            updatePhotographersGrid(data.photographers || data.data || []);
            updateResultCount(data.count || data.total || 0);
            hideError();
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Filter error:', error);
                showError('Error loading photographers. Please try again.');
            }
        })
        .finally(() => {
            showLoading(false);
            currentRequest = null;
        });
    }

    function collectFilterData() {
        const data = {
            search: searchInput.value.trim(),
            sort: sortSelect.value,
            min_price: minPriceInput.value.trim(),
            max_price: maxPriceInput.value.trim(),
            specialties: [],
            locations: [],
            availability: [],
            rating: null
        };
        
        // Collect category checkboxes
        document.querySelectorAll('.category-checkbox:checked').forEach(checkbox => {
            data.specialties.push(checkbox.value);
        });
        
        // Collect location checkboxes
        document.querySelectorAll('.location-checkbox:checked').forEach(checkbox => {
            data.locations.push(checkbox.value);
        });
        
        // Collect availability checkboxes
        document.querySelectorAll('.availability-checkbox:checked').forEach(checkbox => {
            data.availability.push(checkbox.value);
        });
        
        // Collect rating radio
        const checkedRating = document.querySelector('.rating-radio:checked');
        if (checkedRating) {
            data.rating = checkedRating.value;
        }
        
        return data;
    }

    function updatePhotographersGrid(photographers) {
        if (!photographers || photographers.length === 0) {
            photographersGrid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="glass-effect rounded-2xl p-8">
                        <i class="fas fa-search text-6xl text-white/50 mb-4"></i>
                        <h3 class="text-2xl font-bold text-white mb-2">No photographers found</h3>
                        <p class="text-white/70 mb-6">Try adjusting your search criteria or browse all photographers.</p>
                        <button onclick="clearAllFilters()" 
                                class="inline-block bg-white/20 hover:bg-white/30 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30">
                            Clear Filters
                        </button>
                    </div>
                </div>`;
            return;
        }
        
        photographersGrid.innerHTML = photographers.map(photographer => createPhotographerCard(photographer)).join('');
    }

    function createPhotographerCard(photographer) {
        // Handle packages array - find minimum price
        const minPrice = photographer.packages && photographer.packages.length > 0 
            ? Math.min(...photographer.packages.map(p => parseFloat(p.price || 0)))
            : null;
        
        // Handle specialties array - get display names
        const specialtiesDisplay = photographer.specialties ? photographer.specialties.slice(0, 2) : [];
        const specialtiesSlugs = photographer.specialties ? photographer.specialties.map(s => s.name ? s.name.toLowerCase().replace(/\s+/g, '-') : s.slug || '').join(',') : '';
        
        // Handle cities array - get display names and slugs
        const citiesDisplay = photographer.cities ? photographer.cities.map(c => c.name).join(', ') : '';
        const citiesSlugs = photographer.cities ? photographer.cities.map(c => c.name ? c.name.toLowerCase().replace(/\s+/g, '-') : c.slug || '').join(',') : '';
        
        // Handle profile photo URL
        const profilePhotoUrl = photographer.profile_photo ? 
            (photographer.profile_photo.startsWith('http') ? photographer.profile_photo : `/storage/${photographer.profile_photo}`) : 
            null;
        
        // Calculate packages count
        const packagesCount = photographer.packages ? photographer.packages.length : 0;
        
        return `
            <div class="glass-effect rounded-2xl overflow-hidden card-hover transition-all duration-300 photographer-card" 
                data-categories="${specialtiesSlugs}"
                data-locations="${citiesSlugs}"
                data-price="${minPrice || 0}"
                data-rating="${photographer.rating || 0}">
                <div class="relative">
                    ${profilePhotoUrl ? 
                        `<img src="${profilePhotoUrl}" alt="${photographer.name}" class="w-full h-48 object-cover">` :
                        `<div class="w-full h-48 bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-camera text-4xl text-white/80 mb-2"></i>
                                <p class="text-white/60 text-sm">${photographer.name || 'Unknown Photographer'}</p>
                            </div>
                        </div>`
                    }
                    
                    ${photographer.is_available ? 
                        '<div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">Available</div>' : ''
                    }
                    
                    ${photographer.instant_booking ? 
                        '<div class="absolute top-4 left-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg"><i class="fas fa-bolt mr-1"></i>Instant</div>' : ''
                    }

                    ${photographer.weekend_available ? 
                        '<div class="absolute bottom-4 left-4 bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-semibold shadow-lg">Weekend</div>' : ''
                    }
                </div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-white mb-1 truncate">${photographer.name || 'Unknown Photographer'}</h3>
                            <div class="flex flex-wrap gap-1 mb-2">
                                ${specialtiesDisplay.map(specialty => 
                                    `<span class="text-white/70 text-sm bg-white/10 px-2 py-1 rounded-full">${specialty.name || specialty}</span>`
                                ).join('')}
                                ${photographer.specialties && photographer.specialties.length > 2 ? 
                                    `<span class="text-white/50 text-sm">+${photographer.specialties.length - 2}</span>` : ''
                                }
                            </div>
                        </div>
                        ${photographer.rating ? 
                            `<div class="flex items-center bg-yellow-500/20 px-2 py-1 rounded-lg ml-4">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="text-white font-semibold">${parseFloat(photographer.rating).toFixed(1)}</span>
                                ${photographer.reviews_count ? 
                                    `<span class="text-white/70 text-sm ml-1">(${photographer.reviews_count})</span>` : ''
                                }
                            </div>` : ''
                        }
                    </div>
                    
                    ${citiesDisplay ? 
                        `<div class="flex items-center text-white/70 text-sm mb-3">
                            <i class="fas fa-map-marker-alt mr-2 text-white/50"></i>
                            ${citiesDisplay}
                        </div>` : ''
                    }
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-white">
                            <span class="text-sm text-white/70">Starting from</span>
                            ${minPrice ? 
                                `<div class="text-xl font-bold">Rp ${new Intl.NumberFormat('id-ID').format(minPrice)}</div>` :
                                '<div class="text-lg text-white/70">Contact for Price</div>'
                            }
                        </div>
                        
                        <div class="flex items-center space-x-1">
                            ${packagesCount > 0 ? 
                                `<span class="bg-white/20 text-white text-xs px-2 py-1 rounded-full">${packagesCount} packages</span>` : ''
                            }
                        </div>
                    </div>
                    
                    <div class="flex space-x-3">
                        <a href="/photographers/${photographer.id}" 
                        class="flex-1 bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 text-center backdrop-blur-sm border border-white/20 hover:border-white/40">
                            View Profile
                        </a>
                        ${photographer.instant_booking ? 
                            `<a href="/booking/create/${photographer.id}" 
                            class="flex-1 bg-gradient-secondary hover:opacity-90 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 text-center shadow-lg hover:shadow-xl">
                                Book Now
                            </a>` :
                            `<a href="/photographers/${photographer.id}#contact" 
                            class="flex-1 bg-gradient-secondary hover:opacity-90 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 text-center shadow-lg hover:shadow-xl">
                                Contact
                            </a>`
                        }
                    </div>
                </div>
            </div>`;
    }

    function updateResultCount(count) {
        resultCount.textContent = count || 0;
    }

    function showLoading(show) {
        if (show) {
            loadingIndicator.style.display = 'block';
            photographersGrid.style.opacity = '0.5';
        } else {
            loadingIndicator.style.display = 'none';
            photographersGrid.style.opacity = '1';
        }
    }

    function showError(message) {
        let errorDiv = document.getElementById('errorMessage');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'errorMessage';
            errorDiv.className = 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8';
            document.querySelector('.bg-gradient-auth').appendChild(errorDiv);
        }
        
        errorDiv.innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>${message}</span>
            </div>`;
        errorDiv.style.display = 'block';
        
        // Auto hide after 5 seconds
        setTimeout(hideError, 5000);
    }

    function hideError() {
        const errorDiv = document.getElementById('errorMessage');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }

    function clearAllFilters() {
        // Clear form inputs
        searchInput.value = '';
        sortSelect.value = 'recommended';
        minPriceInput.value = '';
        maxPriceInput.value = '';
        
        // Clear checkboxes and radios
        document.querySelectorAll('.category-checkbox, .location-checkbox, .availability-checkbox, .rating-radio').forEach(input => {
            input.checked = false;
        });
        
        // Close all filter dropdowns
        document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
        
        // Remove active class from tabs
        filterTabs.forEach(tab => tab.classList.remove('filter-active'));
        
        // Clear URL parameters and reload
        window.location.href = window.location.pathname;
    }

    // Global function for clear filters button in no results message
    window.clearAllFilters = clearAllFilters;

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.filter-tab') && !e.target.closest('.filter-dropdown')) {
            document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
            filterTabs.forEach(tab => tab.classList.remove('filter-active'));
        }
    });

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        location.reload();
    });

    console.log('Photographer finder initialized successfully');
});
</script>
@endpush