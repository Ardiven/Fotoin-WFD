@extends('layout.app')

@section('title', 'Photographer Profile - FotoIn')

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
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.2);
    }
    .image-hover:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    .text-glow {
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }
    .profile-image {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }
    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    .portfolio-item {
        aspect-ratio: 1;
        overflow: hidden;
        border-radius: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .portfolio-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .portfolio-item:hover img {
        transform: scale(1.1);
    }
    .review-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.08);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-auth">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.4"><circle cx="7" cy="7" r="1"/><circle cx="27" cy="7" r="1"/><circle cx="47" cy="7" r="1"/><circle cx="7" cy="27" r="1"/><circle cx="27" cy="27" r="1"/><circle cx="47" cy="27" r="1"/><circle cx="7" cy="47" r="1"/><circle cx="27" cy="47" r="1"/><circle cx="47" cy="47" r="1"/></g></g></svg>')"></div>
    </div>

    <!-- Back Button -->
    <div class="relative pt-8 pb-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-white/80 hover:text-white transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Photographers
            </a>
        </div>
    </div>

    <!-- Profile Header -->
    <div class="relative pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-effect rounded-2xl p-8 border border-white/20">
                <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
                    <!-- Profile Image -->
                    <div class="flex-shrink-0">
                        <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722163/AbhirajK/Abhirajk%20mykare.webp" 
                             alt="{{ $photographer->name }}" class="profile-image">
                        <div class="text-center mt-4">
                            <span class="bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm border border-green-500/30">
                                <i class="fas fa-circle text-green-400 mr-1 text-xs"></i>
                                Available
                            </span>
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-1 text-center lg:text-left">
                        <h1 class="text-4xl font-bold text-white mb-2 text-glow">{{ $photographer->name }}</h1>
                        
                        <!-- Specialties -->
                        <div class="mb-4">
                            @foreach($photographer->specialties as $specialty)
                                <span class="inline-block bg-white/10 text-white px-3 py-1 rounded-full text-sm mr-2 mb-2 border border-white/20">
                                    {{ $specialty->name }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Rating -->
                        <div class="flex items-center justify-center lg:justify-start mb-4">
                            <div class="flex text-yellow-400 mr-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-white font-semibold mr-2">4.9</span>
                            <span class="text-white/70">(127 reviews)</span>
                        </div>

                        <!-- Location and Experience -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="flex items-center justify-center lg:justify-start">
                                <i class="fas fa-map-marker-alt text-white/70 mr-2 w-5 h-6"></i>
                                @foreach($photographer->cities as $city)
                                <span class="inline-block bg-white/10 text-white px-3 py-1 rounded-full text-sm mr-2 mb-2 border border-white/20">{{ $city->name }}</span>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-center lg:justify-start">
                                <i class="fas fa-calendar-alt text-white/70 mr-2"></i>
                                <span class="text-white">{{$photographer->photographerProfile->experience_years}} Years Experience</span>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-white">200+</div>
                                <div class="text-white/70 text-sm">Projects</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">150+</div>
                                <div class="text-white/70 text-sm">Happy Clients</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">24h</div>
                                <div class="text-white/70 text-sm">Response Time</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column - About & Portfolio -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- About Section -->
                    <div class="glass-effect rounded-2xl p-6 border border-white/20">
                        <h2 class="text-2xl font-bold text-white mb-4">About Me</h2>
                        <p class="text-white/80 leading-relaxed mb-4">
                            {{ $photographer->photographerProfile->bio }}
                        </p>
                    </div>

                    <!-- Portfolio Section -->
                    <div class="glass-effect rounded-2xl p-6 border border-white/20">
                        <h2 class="text-2xl font-bold text-white mb-6">Portfolio</h2>
                        <div class="portfolio-grid">
                            @foreach($photographer->portfolios as $portfolio) 
                            <div class="portfolio-item image-hover transition-all duration-300">  
                                <img src="{{ asset('storage/' . $portfolio->image_path) }}" alt="Portfolio 1">
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-6">
                            <a href="{{ route('customer.photographers.showcase', $photographer) }}" class="bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30">
                                View Full Portfolio
                            </a>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="glass-effect rounded-2xl p-6 border border-white/20">
                        <h2 class="text-2xl font-bold text-white mb-6">Client Reviews</h2>
                        <div class="space-y-4">
                            <!-- Review 1 -->
                            <div class="review-card rounded-xl p-4 border border-white/10">
                                <div class="flex items-start gap-4">
                                    <img src="https://i.pravatar.cc/48?img=1" alt="Client" class="w-12 h-12 rounded-full">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h4 class="text-white font-semibold">Sarah Johnson</h4>
                                            <div class="flex text-yellow-400 text-sm">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                        <p class="text-white/80 text-sm">
                                            "Amazing work! The photographer captured our wedding perfectly. 
                                            Professional, creative, and the photos exceeded our expectations."
                                        </p>
                                        <span class="text-white/60 text-xs">2 weeks ago</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Review 2 -->
                            <div class="review-card rounded-xl p-4 border border-white/10">
                                <div class="flex items-start gap-4">
                                    <img src="https://i.pravatar.cc/48?img=2" alt="Client" class="w-12 h-12 rounded-full">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h4 class="text-white font-semibold">Michael Chen</h4>
                                            <div class="flex text-yellow-400 text-sm">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                        <p class="text-white/80 text-sm">
                                            "Excellent portrait session! Very professional and made us feel comfortable. 
                                            The final photos were stunning and delivered quickly."
                                        </p>
                                        <span class="text-white/60 text-xs">1 month ago</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Review 3 -->
                            <div class="review-card rounded-xl p-4 border border-white/10">
                                <div class="flex items-start gap-4">
                                    <img src="https://i.pravatar.cc/48?img=3" alt="Client" class="w-12 h-12 rounded-full">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h4 class="text-white font-semibold">Emily Rodriguez</h4>
                                            <div class="flex text-yellow-400 text-sm">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                            </div>
                                        </div>
                                        <p class="text-white/80 text-sm">
                                            "Great experience overall. The photographer was punctual and captured 
                                            beautiful moments at our corporate event."
                                        </p>
                                        <span class="text-white/60 text-xs">2 months ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-6">
                            <button class="bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/30">
                                View All Reviews
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Packages & Contact -->
                <div class="space-y-8">
                    
                    <!-- Packages Section -->
                    <div class="glass-effect rounded-2xl p-6 border border-white/20">
                        <h2 class="text-2xl font-bold text-white mb-6">Packages</h2>
                        <div class="space-y-4"> 
                            @foreach($photographer->packages as $package)
                            <div class="card-hover bg-white/5 rounded-xl p-4 border border-white/10 transition-all duration-300">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-white font-semibold">{{ $package->name }}</h3>
                                    <span class="text-white font-bold">Rp. {{ number_format($package->price, 0, ',', '.') }}</span>
                                </div>
                                <p class="text-white/70 text-sm mb-3">{{ $package->description }}</p>
                                <ul class="text-white/60 text-xs space-y-1 pb-6">
                                    @foreach($package->features as $feature)
                                    <li><i class="fas fa-check text-green-400 mr-2"></i>{{ $feature->name }}</li>
                                    @endforeach
                                </ul>
                                <a href="{{route('customer.bookings', $package)}}" class="w-full bg-gradient-secondary hover:opacity-90 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300">
                                    Select Package
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="glass-effect rounded-2xl p-6 border border-white/20">
                        <h2 class="text-2xl font-bold text-white mb-6">Get in Touch</h2>
                        
                        <!-- Contact Info -->
                        <div class="space-y-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-phone text-white/70 mr-3 w-5"></i>
                                <span class="text-white">{{$photographer->photographerProfile->phone}}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-white/70 mr-3 w-5"></i>
                                <span class="text-white">{{ $photographer->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-instagram text-white/70 mr-3 w-5"></i>
                                <span class="text-white">{{'@'. $photographer->name}}</span>
                            </div>
                        </div>

                        <!-- Quick Contact Form -->
                        <form class="space-y-4">
                            <div>
                                <input type="text" placeholder="Your Name" 
                                       class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white placeholder-white/50 focus:outline-none focus:border-white/40">
                            </div>
                            <div>
                                <input type="email" placeholder="Your Email" 
                                       class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white placeholder-white/50 focus:outline-none focus:border-white/40">
                            </div>
                            <div>
                                <textarea placeholder="Your Message" rows="4" 
                                          class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white placeholder-white/50 focus:outline-none focus:border-white/40 resize-none"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-gradient-secondary hover:opacity-90 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </button>
                        </form>

                        <!-- Social Media -->
                        <div class="flex justify-center space-x-4 mt-6 pt-6 border-t border-white/10">
                            <a href="#" class="text-white/70 hover:text-white transition-colors duration-300">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            <a href="#" class="text-white/70 hover:text-white transition-colors duration-300">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                            <a href="#" class="text-white/70 hover:text-white transition-colors duration-300">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="text-white/70 hover:text-white transition-colors duration-300">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Portfolio image lightbox effect
        const portfolioItems = document.querySelectorAll('.portfolio-item');
        portfolioItems.forEach(item => {
            item.addEventListener('click', function() {
                // Simple zoom effect - you can integrate with a lightbox library
                const img = this.querySelector('img');
                const overlay = document.createElement('div');
                overlay.className = 'fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50';
                overlay.innerHTML = `
                    <div class="relative max-w-4xl max-h-full p-4">
                        <img src="${img.src}" alt="${img.alt}" class="max-w-full max-h-full object-contain rounded-lg">
                        <button class="absolute top-2 right-2 text-white text-2xl hover:opacity-70" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                document.body.appendChild(overlay);
                
                // Close on click outside
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) {
                        overlay.remove();
                    }
                });
            });
        });

        // Smooth scrolling for back button
        const backButton = document.querySelector('a[href*="previous"]');
        if (backButton) {
            backButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.history.back();
            });
        }

        // Form submission handler
        const contactForm = document.querySelector('form');
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simple success feedback
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            submitButton.disabled = true;
            
            setTimeout(() => {
                submitButton.innerHTML = '<i class="fas fa-check mr-2"></i>Message Sent!';
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                    this.reset();
                }, 2000);
            }, 1500);
        });

        // Add parallax effect to background
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.fixed');
            if (parallax) {
                const speed = scrolled * 0.3;
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });
    });
</script>
@endpush