    @extends('layout.app')

    @section('title', 'FotoIn - Professional Photography Platform')

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
            transition: backdrop-filter 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }
        .category-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        .category-hover:hover .glass-dark {
            backdrop-filter: blur(0px);
            background: rgba(0, 0, 0, 0.1);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .text-glow {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }
    </style>
    @endpush

    @section('content')
    <div class="min-h-screen bg-gradient-auth">
        <!-- Background Pattern -->
        <div class="fixed inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.4"><circle cx="7" cy="7" r="1"/><circle cx="27" cy="7" r="1"/><circle cx="47" cy="7" r="1"/><circle cx="7" cy="27" r="1"/><circle cx="27" cy="27" r="1"/><circle cx="47" cy="27" r="1"/><circle cx="7" cy="47" r="1"/><circle cx="27" cy="47" r="1"/><circle cx="47" cy="47" r="1"/></g></g></svg>')"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 text-glow animate-float">
                        Best Photographers
                    </h1>
                    <p class="text-xl text-white/80 max-w-2xl mx-auto">
                        Discover and book professional photographers for your special moments
                    </p>
                </div>

                <!-- Photographer Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Photographer Card 1 -->
                    @foreach($photographers as $photographer)
                    @if($photographer->packages->count() > 0)
                    <div class="glass-effect rounded-2xl overflow-hidden border border-white/20 card-hover transition-all duration-500">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $photographer->profile_photo) }}" 
                                alt="Alex Johnson" class="w-full h-48 object-cover">
                            <div class="absolute top-3 right-3">
                                <span class="bg-white/20 backdrop-blur-sm text-white px-2 py-1 rounded-full text-xs border border-white/30">
                                    Available
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-white mb-1">{{$photographer->name}}</h3>
                            @foreach($photographer->specialties as $specialty)
                                <span class="text-white/70 text-sm mr-2">{{$specialty->name}}</span>
                            @endforeach
                            <div class="mb-4">
                                <h4 class="text-white/90 font-medium mb-2">{{$photographer->packages()->orderBy('price', 'asc')->first()->name}}</h4>
                                <p class="text-white font-bold text-lg">Start From Rp. {{ number_format($photographer->packages()->orderBy('price', 'asc')->first()->price, 0, ',', '.') }}</p>
                            </div>
                            
                            <div class="flex items-center mb-4">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="text-white/70 text-sm ml-2">(4.9)</span>
                            </div>
                            
                            <a href="{{ route('customer.photographers.show', $photographer) }}" class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30">
                                <i class="fas fa-eye mr-2"></i>
                                View Profile
                            </a>
                        </div>
                    </div>
                    @endif

                    @endforeach
                </div>
            </div>
        </div>

        <!-- Featured Categories -->
        <div class="relative py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-white mb-4 text-glow">Featured Categories</h2>
                    <p class="text-white/80 text-lg">Explore photography services by category or location</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Category 1 -->
                    <div class="relative category-hover transition-all duration-500 rounded-2xl overflow-hidden">
                        <img src="https://i.pinimg.com/736x/fa/3b/f1/fa3bf1463710297d6031b522e0baf7ef.jpg" 
                            alt="Nearby Photographers" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 glass-dark rounded-2xl"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-map-marker-alt text-white mr-2"></i>
                                <span class="text-white font-semibold text-xl">Nearby</span>
                            </div>
                            <p class="text-white/80 text-sm">Find photographers in your area</p>
                        </div>
                    </div>

                    <!-- Category 2 -->
                    <div class="relative category-hover transition-all duration-500 rounded-2xl overflow-hidden">
                        <img src="https://i.pinimg.com/736x/4b/e3/b4/4be3b4cff1484da1d5802de5f44a8714.jpg" 
                            alt="Explore by City" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 glass-dark rounded-2xl"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-city text-white mr-2"></i>
                                <span class="text-white font-semibold text-xl">Explore by City</span>
                            </div>
                            <p class="text-white/80 text-sm">Browse by major cities</p>
                        </div>
                    </div>

                    <!-- Category 3 -->
                    <div class="relative category-hover transition-all duration-500 rounded-2xl overflow-hidden">
                        <img src="https://i.pinimg.com/736x/21/0e/41/210e41437997d058e952f86b182f7a4f.jpg" 
                            alt="Explore by Categories" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 glass-dark rounded-2xl"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-th-large text-white mr-2"></i>
                                <span class="text-white font-semibold text-xl">By Categories</span>
                            </div>
                            <p class="text-white/80 text-sm">Wedding, Portrait, Event & more</p>
                        </div>
                    </div>

                    <!-- Category 4 -->
                    <div class="relative category-hover transition-all duration-500 rounded-2xl overflow-hidden">
                        <img src="https://i.pinimg.com/736x/76/f4/63/76f463f2a6f8f2d6a0a2fe6c0c823f60.jpg" 
                            alt="Ask AI" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 glass-dark rounded-2xl"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-robot text-white mr-2"></i>
                                <span class="text-white font-semibold text-xl">Ask AI</span>
                            </div>
                            <p class="text-white/80 text-sm">Get personalized recommendations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="relative py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="glass-effect rounded-2xl p-8 border border-white/20">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">1000+</div>
                            <div class="text-white/80">Professional Photographers</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">5000+</div>
                            <div class="text-white/80">Happy Customers</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">50+</div>
                            <div class="text-white/80">Cities Covered</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">4.9★</div>
                            <div class="text-white/80">Average Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
        // Add smooth scrolling and interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add parallax effect to background
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const parallax = document.querySelector('.fixed');
                if (parallax) {
                    const speed = scrolled * 0.5;
                    parallax.style.transform = `translateY(${speed}px)`;
                }
            });

            // Add hover effects to cards
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Add click effects to category cards
            const categories = document.querySelectorAll('.category-hover');
            categories.forEach(category => {
                category.addEventListener('click', function() {
                    // Add click animation
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1.05)';
                    }, 100);
                });
            });
        });
    </script>
    @endpush