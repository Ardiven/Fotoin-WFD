@extends('layout.app')

@section('title', 'Portfolio - {{ $photographer->name }} - FotoIn')

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
    }
    .glass-dark {
        backdrop-filter: blur(10px);
        background: rgba(0, 0, 0, 0.2);
    }
    .text-glow {
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }
    
    /* Portfolio Card Styles */
    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .portfolio-card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
        backdrop-filter: blur(10px);
    }
    
    .portfolio-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.15);
    }
    
    .portfolio-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .portfolio-card:hover .portfolio-card-image {
        transform: scale(1.05);
    }
    
    .portfolio-card-content {
        padding: 1.5rem;
    }
    
    .portfolio-card-title {
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    
    .portfolio-card-specialty {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.875rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        display: inline-block;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Filter Buttons */
    .filter-btn {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .filter-btn:hover, .filter-btn.active {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }
    
    /* Lightbox Styles - Simplified */
    .lightbox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .lightbox.active {
        opacity: 1;
        visibility: visible;
    }
    
    .lightbox-content {
        max-width: 90vw;
        max-height: 90vh;
        position: relative;
    }
    
    .lightbox-content img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 0.5rem;
    }
    
    .lightbox-close {
        position: absolute;
        top: -3rem;
        right: 0;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        transition: opacity 0.3s ease;
        background: rgba(0, 0, 0, 0.5);
        padding: 0.5rem;
        border-radius: 50%;
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .lightbox-close:hover {
        opacity: 0.7;
    }
    
    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 2rem;
        cursor: pointer;
        transition: opacity 0.3s ease;
        background: rgba(0, 0, 0, 0.5);
        padding: 1rem;
        border-radius: 50%;
        width: 4rem;
        height: 4rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .lightbox-nav:hover {
        opacity: 0.7;
    }
    
    .lightbox-prev {
        left: -5rem;
    }
    
    .lightbox-next {
        right: -5rem;
    }
    
    /* Stats Section */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        background: rgba(255, 255, 255, 0.15);
    }
    
    /* Animation for portfolio cards */
    .portfolio-card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .portfolio-card.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .portfolio-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .lightbox-nav {
            display: none;
        }
        
        .lightbox-close {
            top: 1rem;
            right: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-auth">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.4"><circle cx="7" cy="7" r="1"/><circle cx="27" cy="7" r="1"/><circle cx="47" cy="7" r="1"/><circle cx="7" cy="27" r="1"/><circle cx="27" cy="27" r="1"/><circle cx="47" cy="27" r="1"/><circle cx="7" cy="47" r="1"/><circle cx="27" cy="47" r="1"/><circle cx="47" cy="47" r="1"/></g></g></svg>')"></div>
    </div>

    <!-- Header Section -->
    <div class="relative pt-8 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('customer.photographers.show', $photographer) }}" class="inline-flex items-center text-white/80 hover:text-white transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Profile
                </a>
            </div>

            <!-- Title Section -->
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-white mb-4 text-glow">{{ $photographer->name }}'s Portfolio</h1>
                <p class="text-white/80 text-lg max-w-2xl mx-auto">
                    Explore my collection of captured moments and creative vision through various photography styles and projects.
                </p>
            </div>

            <!-- Stats Section -->
            <div class="stats-grid mb-12">
                <div class="stat-card">
                    <div class="text-3xl font-bold text-white mb-2">{{ $photographer->portfolios->count() }}+</div>
                    <div class="text-white/70">Photos</div>
                </div>
                <div class="stat-card">
                    <div class="text-3xl font-bold text-white mb-2">{{ $photographer->specialties->count() }}</div>
                    <div class="text-white/70">Specialties</div>
                </div>
                <div class="stat-card">
                    <div class="text-3xl font-bold text-white mb-2">{{$photographer->photographerProfile->experience_years}}</div>
                    <div class="text-white/70">Years Experience</div>
                </div>
                <div class="stat-card">
                    <div class="text-3xl font-bold text-white mb-2">150+</div>
                    <div class="text-white/70">Happy Clients</div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="text-center mb-8">
                <div class="inline-flex flex-wrap gap-2 p-1 glass-effect rounded-2xl border border-white/20">
                    <button class="filter-btn active" data-filter="all">All</button>
                    @foreach($photographer->specialties as $specialty)
                        <button class="filter-btn" data-filter="{{ strtolower($specialty->name) }}">{{ $specialty->name }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Grid -->
    <div class="relative pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="portfolio-grid" id="portfolioGrid">
                @foreach($photographer->portfolios as $index => $portfolio)
                    <div class="portfolio-card" 
                         data-category="{{ isset($portfolio->category) ? strtolower($portfolio->category) : 'general' }}"
                         data-index="{{ $index }}"
                         data-image="{{ asset('storage/' . $portfolio->image_path) }}"
                         style="animation-delay: {{ $index * 0.1 }}s;">
                        <img src="{{ asset('storage/' . $portfolio->image_path) }}" 
                             alt="{{ $portfolio->title ?? 'Portfolio Image' }}"
                             class="portfolio-card-image"
                             loading="lazy">
                        <div class="portfolio-card-content">
                            <h3 class="portfolio-card-title">{{ $portfolio->title ?? 'Professional Photography' }}</h3>
                            <span class="portfolio-card-specialty">
                                {{ $portfolio->category ?? $photographer->specialties->first()->name ?? 'Photography' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button id="loadMoreBtn" class="bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-plus mr-2"></i>
                    Load More
                </button>
            </div>
        </div>
    </div>

    <!-- Contact CTA Section -->
    <div class="relative pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="glass-effect rounded-2xl p-8 border border-white/20">
                <h2 class="text-3xl font-bold text-white mb-4">Like What You See?</h2>
                <p class="text-white/80 mb-6">
                    Ready to create something amazing together? Let's discuss your vision and bring it to life.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('customer.photographers.show', $photographer) }}" 
                       class="bg-gradient-secondary hover:opacity-90 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-300">
                        <i class="fas fa-envelope mr-2"></i>
                        Get in Touch
                    </a>
                    <a href="{{ route('customer.photographers.show', $photographer) }}" 
                       class="bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30">
                        <i class="fas fa-package mr-2"></i>
                        View Packages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox - Simplified -->
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <div class="lightbox-close" id="lightboxClose">
                <i class="fas fa-times"></i>
            </div>
            <div class="lightbox-nav lightbox-prev" id="lightboxPrev">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="lightbox-nav lightbox-next" id="lightboxNext">
                <i class="fas fa-chevron-right"></i>
            </div>
            <img id="lightboxImg" src="" alt="">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Portfolio filtering
        const filterButtons = document.querySelectorAll('.filter-btn');
        const portfolioCards = document.querySelectorAll('.portfolio-card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                
                portfolioCards.forEach(card => {
                    if (filter === 'all' || card.dataset.category === filter) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.classList.add('visible');
                        }, 100);
                    } else {
                        card.classList.remove('visible');
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // Lightbox functionality - Simplified
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightboxImg');
        const lightboxClose = document.getElementById('lightboxClose');
        const lightboxPrev = document.getElementById('lightboxPrev');
        const lightboxNext = document.getElementById('lightboxNext');
        
        let currentIndex = 0;
        let visibleCards = [];
        
        function updateVisibleCards() {
            visibleCards = Array.from(portfolioCards).filter(card => 
                card.style.display !== 'none'
            );
        }
        
        function openLightbox(index) {
            updateVisibleCards();
            currentIndex = index;
            const card = visibleCards[currentIndex];
            const imageSrc = card.dataset.image;
            
            lightboxImg.src = imageSrc;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        function nextImage() {
            updateVisibleCards();
            currentIndex = (currentIndex + 1) % visibleCards.length;
            openLightbox(currentIndex);
        }
        
        function prevImage() {
            updateVisibleCards();
            currentIndex = (currentIndex - 1 + visibleCards.length) % visibleCards.length;
            openLightbox(currentIndex);
        }
        
        // Add click listeners to portfolio cards
        portfolioCards.forEach((card, index) => {
            card.addEventListener('click', function() {
                updateVisibleCards();
                const visibleIndex = visibleCards.indexOf(card);
                if (visibleIndex !== -1) {
                    openLightbox(visibleIndex);
                }
            });
        });
        
        // Lightbox controls
        lightboxClose.addEventListener('click', closeLightbox);
        lightboxNext.addEventListener('click', nextImage);
        lightboxPrev.addEventListener('click', prevImage);
        
        // Close lightbox on background click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (lightbox.classList.contains('active')) {
                switch(e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowLeft':
                        prevImage();
                        break;
                    case 'ArrowRight':
                        nextImage();
                        break;
                }
            }
        });

        // Load more functionality
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        let itemsToShow = 12;
        
        function showMoreItems() {
            const hiddenCards = Array.from(portfolioCards).slice(itemsToShow);
            
            hiddenCards.slice(0, 6).forEach((card, index) => {
                setTimeout(() => {
                    card.style.display = 'block';
                    card.classList.add('visible');
                }, index * 100);
            });
            
            itemsToShow += 6;
            
            if (itemsToShow >= portfolioCards.length) {
                loadMoreBtn.style.display = 'none';
            }
        }
        
        loadMoreBtn.addEventListener('click', showMoreItems);
        
        // Initially show first 12 items
        portfolioCards.forEach((card, index) => {
            if (index >= 12) {
                card.style.display = 'none';
            } else {
                setTimeout(() => {
                    card.classList.add('visible');
                }, index * 100);
            }
        });
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);
        
        portfolioCards.forEach(card => {
            observer.observe(card);
        });

        // Parallax effect
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.fixed');
            if (parallax) {
                const speed = scrolled * 0.2;
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });
    });
</script>
@endpush