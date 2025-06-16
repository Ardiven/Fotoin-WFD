@extends('layout.dashboard')

@section('title', 'Manage Packages')

@section('page-header')
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-white">Manage Packages</h2>
        <a href="{{ route('photographer.packages.create') }}" class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
            <i class="fas fa-plus mr-2"></i>Add New Package
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Package Stats -->
        @if(isset($stats) && $stats['total'] > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="stats-card border border-white/20 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-sm">Total Packages</p>
                            <p class="text-white text-2xl font-bold">{{ $stats['total'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-box text-blue-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card border border-white/20 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-sm">Active Packages</p>
                            <p class="text-white text-2xl font-bold">{{ $stats['active'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card border border-white/20 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-sm">Popular Packages</p>
                            <p class="text-white text-2xl font-bold">{{ $stats['popular'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card border border-white/20 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-sm">Average Price</p>
                            <p class="text-white text-2xl font-bold">Rp {{ number_format($stats['average_price'], 0, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-purple-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <form method="GET" action="{{ route('photographer.packages.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="input-group">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-3 form-input rounded-lg bg-white/10 border border-white/30"
                               placeholder="Search packages...">
                       
                    </div>
                </div>
                <div class="flex gap-2">
                    <select name="status" class="px-4 py-3 form-input rounded-lg bg-white/10 border border-white/30">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <select name="sort" class="px-4 py-3 form-input rounded-lg bg-white/10 border border-white/30">
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Sort by Name</option>
                        <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Sort by Price</option>
                        <option value="created" {{ request('sort') === 'created' ? 'selected' : '' }}>Sort by Date</option>
                    </select>
                    <button type="submit" class="px-4 py-3 glass-effect border border-white/20 text-white rounded-lg hover:bg-white/10 transition-colors">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="content-glass border border-green-500/40 rounded-lg p-4">
                <div class="flex items-center text-green-300">
                    <i class="fas fa-check-circle mr-3 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="content-glass border border-red-500/40 rounded-lg p-4">
                <div class="flex items-center text-red-300">
                    <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Packages Section -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <h3 class="text-white text-lg font-semibold mb-4">Your Packages</h3>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($packages as $package)
                    <div class="package-card stats-card border border-white/20 rounded-lg p-6 relative group hover:bg-white/10 transition-all duration-300">
                        <!-- Package Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-camera text-blue-400"></i>
                                </div>
                                <h4 class="text-white font-medium">{{ $package->name }}</h4>
                            </div>
                            
                            <!-- Action Buttons - Show on hover -->
                            <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('photographer.packages.edit', $package) }}" 
                                   class="w-8 h-8 bg-blue-500/20 hover:bg-blue-500/40 border border-blue-500/40 rounded-lg flex items-center justify-center text-blue-300 transition-colors"
                                   title="Edit Package">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form method="POST" action="{{ route('photographer.packages.destroy', $package) }}" 
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this package?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-8 h-8 bg-red-500/20 hover:bg-red-500/40 border border-red-500/40 rounded-lg flex items-center justify-center text-red-300 transition-colors"
                                            title="Delete Package">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Package Info -->
                        <div class="space-y-3">
                            <!-- Price -->
                            <div class="flex items-center justify-between py-2 border-b border-white/10">
                                <span class="text-white/60 text-sm">Price</span>
                                <span class="text-white font-semibold">{{ $package->formatted_price }}</span>
                            </div>

                            <!-- Duration -->
                            <div class="flex items-center justify-between py-2 border-b border-white/10">
                                <span class="text-white/60 text-sm">Duration</span>
                                <span class="text-white text-sm">{{ $package->formatted_duration }}</span>
                            </div>

                            <!-- Status & Popular Badge -->
                            <div class="flex items-center justify-between py-2">
                                <span class="text-white/60 text-sm">Status</span>
                                <div class="flex items-center space-x-2">
                                    <span class="status-badge {{ $package->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                        {{ ucfirst($package->status) }}
                                    </span>
                                    @if($package->is_popular)
                                        <span class="status-badge popular-badge">
                                            <i class="fas fa-star mr-1"></i>Popular
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Description Preview -->
                            @if($package->description)
                                <div class="pt-2">
                                    <p class="text-white/60 text-sm line-clamp-2">{{ $package->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    @if(request()->hasAny(['search', 'status']))
                        <div class="col-span-full text-center py-12">
                            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search text-white/40 text-2xl"></i>
                            </div>
                            <h3 class="text-white text-lg font-medium mb-2">No Packages Found</h3>
                            <p class="text-white/60 mb-6">Try adjusting your search or filter criteria</p>
                            <a href="{{ route('photographer.packages.index') }}" class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
                                Clear Filters
                            </a>
                        </div>
                    @else
                        <div class="col-span-full text-center py-12">
                            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-box text-white/40 text-2xl"></i>
                            </div>
                            <h3 class="text-white text-lg font-medium mb-2">No Packages Yet</h3>
                            <p class="text-white/60 mb-6">Start by creating your first photography package</p>
                            <a href="{{ route('photographer.packages.create') }}" class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
                                <i class="fas fa-plus mr-2"></i>Create First Package
                            </a>
                        </div>
                    @endif
                @endforelse

                <!-- Add New Package Card -->
                @if($packages->count() > 0)
                    <div class="stats-card border-2 border-dashed border-white/30 hover:border-white/50 transition-colors cursor-pointer rounded-lg">
                        <a href="{{ route('photographer.packages.create') }}" class="flex flex-col items-center justify-center h-full min-h-[280px] text-center p-6">
                            <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-plus text-green-400 text-2xl"></i>
                            </div>
                            <h4 class="text-white font-medium mb-2">Add New Package</h4>
                            <p class="text-white/60 text-sm">Create a new photography package</p>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pagination -->
        @if($packages->hasPages())
            <div class="flex justify-center">
                {{ $packages->appends(request()->query())->links('pagination.custom') }}
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
    
    .stats-card {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .form-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .form-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    .form-input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.4);
        outline: none;
    }
    
    .package-card {
        transition: all 0.3s ease;
    }
    
    .package-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    
    .status-badge {
        padding: 4px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
    
    .status-active {
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.4);
        color: rgb(134, 239, 172);
    }
    
    .status-inactive {
        background: rgba(107, 114, 128, 0.2);
        border: 1px solid rgba(107, 114, 128, 0.4);
        color: rgb(209, 213, 219);
    }
    
    .popular-badge {
        background: rgba(251, 191, 36, 0.2);
        border: 1px solid rgba(251, 191, 36, 0.4);
        color: rgb(253, 224, 71);
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .input-group {
        position: relative;
    }
    
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('packagesApp', () => ({
            init() {
                // Initialize any data or event listeners here
            }
        }))
    })
</script>
@endpush