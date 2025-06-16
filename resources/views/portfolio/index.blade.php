@extends('layout.dashboard')
@section('title', 'Portfolio')
@section('page-header')
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-white">Portfolio Gallery</h2>
        @auth
            <button onclick="document.getElementById('addPortfolioModal').classList.remove('hidden')" 
                    class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
                <i class="fas fa-plus mr-2"></i>Add New Project
            </button>
        @endauth
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .portfolio-card {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .portfolio-card:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.15);
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

    .featured-badge {
        background: rgba(251, 191, 36, 0.2);
        border: 1px solid rgba(251, 191, 36, 0.4);
        color: rgb(253, 224, 71);
    }

    .specialty-badge {
        background: rgba(59, 130, 246, 0.2);
        border: 1px solid rgba(59, 130, 246, 0.4);
        color: rgb(147, 197, 253);
    }

    .filter-pill {
        padding: 8px 16px;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
    }

    .filter-pill:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .filter-pill.active {
        background: rgba(59, 130, 246, 0.3);
        border-color: rgba(59, 130, 246, 0.5);
        color: white;
    }

    /* Custom pagination styles */
    .pagination-wrapper .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-wrapper .pagination .page-item {
        margin: 0;
    }

    .pagination-wrapper .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        margin: 0;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .pagination-wrapper .pagination .page-link:hover {
        background: rgba(59, 130, 246, 0.2);
        border-color: rgba(59, 130, 246, 0.3);
        color: white;
    }

    .pagination-wrapper .pagination .page-item.active .page-link {
        background: rgb(59, 130, 246);
        border-color: rgb(59, 130, 246);
        color: white;
    }

    .pagination-wrapper .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .pagination-wrapper .pagination {
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .pagination-wrapper .pagination .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
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

@section('content')
    <div class="space-y-6">
        <!-- Stats Section -->
        <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-white">Portfolio Gallery</h2>
        @auth
            <button onclick="document.getElementById('addPortfolioModal').classList.remove('hidden')" 
                    class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
                <i class="fas fa-plus mr-2"></i>Add New Project
            </button>
        @endauth
    </div>
        @if(isset($totalItems))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="stats-card border border-white/20 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-sm">Total Projects</p>
                            <p class="text-white text-2xl font-bold">{{ $totalItems ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-folder text-blue-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card border border-white/20 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-sm">Specialties</p>
                            <p class="text-white text-2xl font-bold">{{ $specialties->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-tags text-green-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card border border-white/20 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-sm">Total Views</p>
                            <p class="text-white text-2xl font-bold">{{ $totalViews ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-eye text-purple-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card border border-white/20 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-sm">Total Likes</p>
                            <p class="text-white text-2xl font-bold">{{ $totalLikes ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-red-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Specialty Filter Pills -->
        {{-- <div class="content-glass border border-white/20 rounded-lg p-6">
            <h3 class="text-white text-lg font-semibold mb-4">Filter by Specialty</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('photographer.portfolio.index') }}" 
                   class="filter-pill {{ !request('specialty_id') ? 'active' : '' }}">
                    All Projects
                </a>
                @foreach($specialties as $specialty)
                    <a href="{{ route('photographer.portfolio.index', ['specialty_id' => $specialty->id]) }}" 
                       class="filter-pill {{ request('specialty_id') == $specialty->id ? 'active' : '' }}">
                        {{ $specialty->name }}
                        <span class="ml-1 text-xs opacity-70">({{ $specialty->portfolio_items_count ?? 0 }})</span>
                    </a>
                @endforeach
            </div>
        </div> --}}

        <!-- Search and Advanced Filters -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <form method="GET" action="{{ route('photographer.portfolio.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Search Projects</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by title or description..."
                                   class="w-full px-4 py-3 pl-10 form-input text-white bg-white/10 border border-white/30 rounded-lg">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Specialty Filter -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Specialty</label>
                        <select name="specialty_id" class="text-white bg-white/10 border border-white/30 w-full px-4 py-3 form-input rounded-lg">
                            <option value="">All Specialties</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Sort Options -->
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Sort By</label>
                        <select name="sort" class="w-full px-4 py-3 form-input rounded-lg text-white bg-white/10 border border-white/30">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                            <option value="specialty" {{ request('sort') == 'specialty' ? 'selected' : '' }}>By Specialty</option>
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured First</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                    <div class="flex flex-wrap items-center gap-4">
                        <label class="flex items-center text-white text-sm">
                            <input type="checkbox" 
                                   name="featured_only" 
                                   value="1" 
                                   {{ request('featured_only') ? 'checked' : '' }}
                                   class="mr-2 rounded">
                            Featured Only
                        </label>
                        
                        <label class="flex items-center text-white text-sm">
                            <input type="checkbox" 
                                   name="recent_only" 
                                   value="1" 
                                   {{ request('recent_only') ? 'checked' : '' }}
                                   class="mr-2 rounded">
                            Last 30 Days
                        </label>
                    </div>
                    
                    <div class="flex space-x-2">
                        <button type="submit" class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10 transition-colors">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('photographer.portfolio.index') }}" class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10 transition-colors">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
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

        <!-- Portfolio Section -->
        <div class="content-glass border border-white/20 rounded-lg p-6">
            <h3 class="text-white text-lg font-semibold mb-4">Portfolio Projects</h3>

            <!-- Portfolio Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($portfolioItems as $item)
                    <div class="portfolio-card border border-white/20 rounded-lg overflow-hidden relative group">
                        <!-- Image Section -->
                        @if($item->image_path)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $item->image_path) }}"
                                     alt="{{ $item->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                
                                <!-- Overlay with Quick Actions -->
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-3">
                                    <a href="{{ route('photographer.portfolio.index', $item) }}" 
                                       class="p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @auth
                                        @if(auth()->user()->id === $item->user_id)
                                            <button onclick="document.getElementById('editPortfolioModal').classList.remove('hidden')" 
                                                    class="p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                                
                                <!-- Featured Badge -->
                                @if($item->is_featured ?? false)
                                    <div class="absolute top-3 left-3">
                                        <span class="status-badge featured-badge">
                                            <i class="fas fa-star mr-1"></i>Featured
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Specialty Badge -->
                                <div class="absolute top-3 right-3">
                                    <span class="status-badge specialty-badge">
                                        {{ $item->specialty->name ?? 'General' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <!-- Placeholder for items without images -->
                            <div class="relative h-48 bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                                <i class="fas fa-image text-gray-500 text-4xl"></i>
                                
                                <!-- Featured Badge -->
                                @if($item->is_featured ?? false)
                                    <div class="absolute top-3 left-3">
                                        <span class="status-badge featured-badge">
                                            <i class="fas fa-star mr-1"></i>Featured
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Specialty Badge -->
                                <div class="absolute top-3 right-3">
                                    <span class="status-badge specialty-badge">
                                        {{ $item->specialty->name ?? 'General' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Content Section -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="text-white font-medium line-clamp-1">
                                    {{ $item->title }}
                                </h4>
                                @auth
                                    @if(auth()->user()->id == $item->user_id)
                                        <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity ml-2">
                                            <button onclick="document.getElementById('editPortfolioModal').classList.remove('hidden')" 
                                                    class="w-8 h-8 bg-blue-500/20 hover:bg-blue-500/40 border border-blue-500/40 rounded-lg flex items-center justify-center text-blue-300 transition-colors"
                                                    title="Edit Portfolio">
                                                <i class="fas fa-edit text-sm"></i>
                                            </button>
                                            <form method="POST" action="{{ route('photographer.portfolio.destroy', $item) }}" 
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
                                    @endif
                                @endauth
                            </div>
                            
                            <div class="space-y-3">
                                <!-- Specialty & Author -->
                                <div class="flex items-center justify-between py-2 border-b border-white/10">
                                    <span class="text-white/60 text-sm">Category</span>
                                    <span class="text-white text-sm">{{ $item->specialty->name ?? 'General' }}</span>
                                </div>

                                <div class="flex items-center justify-between py-2 border-b border-white/10">
                                    <span class="text-white/60 text-sm">Author</span>
                                    <span class="text-white text-sm">{{ $item->user->name ?? 'Unknown' }}</span>
                                </div>
                                
                                @if($item->description)
                                    <div class="pt-2">
                                        <p class="text-white/60 text-sm line-clamp-3">
                                            {{ $item->description }}
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Footer Stats -->
                                <div class="flex items-center justify-between pt-4 border-t border-white/10">
                                    <div class="flex items-center space-x-4 text-white/60 text-xs">
                                        <span class="flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            {{ $item->views ?? 0 }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-heart mr-1"></i>
                                            {{ $item->likes ?? 0 }}
                                        </span>
                                        @if(($item->comments_count ?? 0) > 0)
                                            <span class="flex items-center">
                                                <i class="fas fa-comment mr-1"></i>
                                                {{ $item->comments_count }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <span class="text-white/60 text-xs">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $item->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                                
                                <!-- Action Button -->
                                <div class="pt-2">
                                    <a href="{{ route('photographer.portfolio.index', $item) }}" 
                                       class="w-full px-4 py-2 glass-effect border border-white/20 text-white text-sm rounded-lg hover:bg-white/10 transition-colors text-center block">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    @if(request()->hasAny(['specialty_id', 'search', 'featured_only']))
                        <div class="col-span-full text-center py-12">
                            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search text-white/40 text-2xl"></i>
                            </div>
                            <h3 class="text-white text-lg font-medium mb-2">No Portfolio Items Found</h3>
                            <p class="text-white/60 mb-6">Try adjusting your search or filter criteria</p>
                            <a href="{{ route('photographer.portfolio.index') }}" class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
                                Clear Filters
                            </a>
                        </div>
                    @else
                        <div class="col-span-full text-center py-12">
                            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-folder-open text-white/40 text-2xl"></i>
                            </div>
                            <h3 class="text-white text-lg font-medium mb-2">No Portfolio Items Yet</h3>
                            <p class="text-white/60 mb-6">Start building your portfolio by adding your first project</p>
                            @auth
                                <button onclick="document.getElementById('addPortfolioModal').classList.remove('hidden')" 
                                        class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10">
                                    <i class="fas fa-plus mr-2"></i>Add Your First Project
                                </button>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="glass-effect border border-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/10 inline-block">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Add Projects
                                </a>
                            @endauth
                        </div>
                    @endif

                    <!-- Add New Project Card -->
                    @if($portfolioItems->count() > 0 && auth()->check())
                        <div class="stats-card border-2 border-dashed border-white/30 hover:border-white/50 transition-colors cursor-pointer rounded-lg">
                            <button onclick="document.getElementById('addPortfolioModal').classList.remove('hidden')" class="flex flex-col items-center justify-center h-full min-h-[280px] text-center p-6 w-full">
                                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-plus text-green-400 text-2xl"></i>
                                </div>
                                <h4 class="text-white font-medium mb-2">Add New Project</h4>
                                <p class="text-white/60 text-sm">Create a new portfolio item</p>
                            </button>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        {{-- @if($portfolioItems->hasPages())
            <div class="flex justify-center">
                <div class="pagination-wrapper">
                    {{ $portfolioItems->appends(request()->query())->links() }}
                </div>
            </div>
        @endif --}}

    </div>

    <!-- Add Portfolio Modal (you'll need to create this separately) -->
    @include('portfolio.modals.add-portfolio')

    <!-- Edit Portfolio Modal (you'll need to create this separately) -->
    @if($totalItems > 0){
        @include('portfolio.modals.edit-portfolio')
    }
    @endif

@endsection

@push('scripts')
    <script>
    // Auto-submit form on filter change
    document.addEventListener('DOMContentLoaded', function() {
        const autoSubmitSelects = document.querySelectorAll('select[name="specialty_id"], select[name="sort"]');
        autoSubmitSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    });

    // Edit portfolio function
    function editPortfolio(id) {
        // Show loading state
        const modal = document.getElementById('editPortfolioModal');
        if (!modal) {
            alert('Edit modal not found. Please create the edit modal component.');
            return;
        }

        // Fetch portfolio data and populate edit modal
        fetch(`/portfolio/${id}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Populate form fields
                const fields = ['editPortfolioId', 'editTitle', 'editDescription', 'editSpecialtyId', 'editExternalLink', 'editTechnologies'];
                fields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        const fieldName = fieldId.replace('edit', '').toLowerCase().replace('id', '_id');
                        field.value = data[fieldName] || '';
                    }
                });

                const featuredCheckbox = document.getElementById('editIsFeatured');
                if (featuredCheckbox) {
                    featuredCheckbox.checked = data.is_featured || false;
                }
                
                modal.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading portfolio data. Please try again.');
            });
    }

    // Delete portfolio function
    // Delete portfolio function
    function deletePortfolio(id) {
        if (confirm('Are you sure you want to delete this portfolio item? This action cannot be undone.')) {
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/portfolio/${id}`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }
            
            // Add method spoofing for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal function
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Image preview function
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
        }
    }

    // Form validation
    function validatePortfolioForm(formId) {
        const form = document.getElementById(formId);
        const title = form.querySelector('input[name="title"]');
        const specialty = form.querySelector('select[name="specialty_id"]');
        
        let isValid = true;
        let errors = [];
        
        // Clear previous errors
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-white/20');
        });
        
        // Validate title
        if (!title.value.trim()) {
            showFieldError(title, 'Title is required');
            isValid = false;
        } else if (title.value.trim().length < 3) {
            showFieldError(title, 'Title must be at least 3 characters');
            isValid = false;
        }
        
        // Validate specialty
        if (!specialty.value) {
            showFieldError(specialty, 'Please select a specialty');
            isValid = false;
        }
        
        return isValid;
    }

    // Show field error
    function showFieldError(field, message) {
        field.classList.remove('border-white/20');
        field.classList.add('border-red-500');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-red-400 text-sm mt-1';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }

    // Handle form submission
    function submitPortfolioForm(formId) {
        const form = document.getElementById(formId);
        
        if (!validatePortfolioForm(formId)) {
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;
        
        // You can add additional AJAX handling here if needed
        // For now, let the form submit normally
        
        return true;
    }

    // Search functionality with debounce
    let searchTimeout;
    function handleSearchInput(input) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            input.form.submit();
        }, 500); // 500ms delay
    }

    // Auto-submit checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[name="featured_only"], input[name="recent_only"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                this.form.submit();
            });
        });
        
        // Add search input listener
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                handleSearchInput(this);
            });
        }
    });

    // Smooth scroll to top when pagination changes
    document.addEventListener('DOMContentLoaded', function() {
        const paginationLinks = document.querySelectorAll('.pagination .page-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(() => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }, 100);
            });
        });
    });

    // Handle modal backdrop clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.classList.add('hidden');
            });
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Escape key to close modals
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal:not(.hidden)');
            if (openModal) {
                openModal.classList.add('hidden');
            }
        }
        
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
            }
        }
    });

    // Intersection Observer for lazy loading if needed
    const observeImages = () => {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    };

    // Initialize lazy loading if images with data-src exist
    document.addEventListener('DOMContentLoaded', observeImages);

    // Portfolio card hover effects enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const portfolioCards = document.querySelectorAll('.portfolio-card');
        
        portfolioCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });

    // Toast notification system
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        
        if (type === 'success') {
            toast.className += ' bg-green-500/20 border border-green-500/40 text-green-300';
            toast.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
        } else if (type === 'error') {
            toast.className += ' bg-red-500/20 border border-red-500/40 text-red-300';
            toast.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
        }
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 5000);
    }

    // Export functions for global use
    window.portfolioFunctions = {
        editPortfolio,
        deletePortfolio,
        closeModal,
        previewImage,
        validatePortfolioForm,
        submitPortfolioForm,
        showToast
    };
</script>
@endpush
