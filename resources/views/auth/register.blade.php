@extends('layout.app')

@section('content')
<style>
    .bg-gradient-auth {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
    }
    .glass-form {
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.15);
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
    .input-glow:focus {
        box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
    }
    .form-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 50px rgba(102, 126, 234, 0.2);
    }
    .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
    }
    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
    }
    .floating-shapes::before,
    .floating-shapes::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float-shapes 8s ease-in-out infinite;
    }
    .floating-shapes::before {
        width: 200px;
        height: 200px;
        top: 20%;
        left: 10%;
        animation-delay: -2s;
    }
    .floating-shapes::after {
        width: 150px;
        height: 150px;
        bottom: 20%;
        right: 10%;
        animation-delay: -4s;
    }
    @keyframes float-shapes {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        25% { transform: translateY(-20px) rotate(90deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
        75% { transform: translateY(-30px) rotate(270deg); }
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    @keyframes ripple-animation {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    .radio-glow:checked {
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
    }
    .user-type-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    .user-type-card:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }
    .user-type-card.selected {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.6);
    }
</style>

<!-- Register Section -->
<div class="min-h-screen bg-gradient-auth relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;><circle cx=&quot;7&quot; cy=&quot;7&quot; r=&quot;1&quot;/><circle cx=&quot;27&quot; cy=&quot;7&quot; r=&quot;1&quot;/><circle cx=&quot;47&quot; cy=&quot;7&quot; r=&quot;1&quot;/><circle cx=&quot;7&quot; cy=&quot;27&quot; r=&quot;1&quot;/><circle cx=&quot;27&quot; cy=&quot;27&quot; r=&quot;1&quot;/><circle cx=&quot;47&quot; cy=&quot;27&quot; r=&quot;1&quot;/><circle cx=&quot;7&quot; cy=&quot;47&quot; r=&quot;1&quot;/><circle cx=&quot;27&quot; cy=&quot;47&quot; r=&quot;1&quot;/><circle cx=&quot;47&quot; cy=&quot;47&quot; r=&quot;1&quot;/></g></g></svg>')"></div>
    </div>

    <!-- Floating Shapes -->
    <div class="floating-shapes"></div>

    <!-- Register Form Container -->
    <div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 z-20">
        <div class="max-w-lg w-full space-y-8">
            <!-- Header -->
            <div class="text-center animate-float">
                <div class="mx-auto h-20 w-20 bg-white/20 rounded-full flex items-center justify-center mb-6 border border-white/30">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h2 class="text-4xl font-bold text-white text-glow mb-2">Join FotoIn</h2>
                <p class="text-white/80 text-lg">Create your FotoIn account</p>
            </div>

            <!-- Register Form -->
            <form action="{{ route('register') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <div class="glass-form rounded-2xl p-8 border border-white/20 form-hover transition-all duration-500">
                    <div class="space-y-6">
                        <!-- Name Field -->
                        <div class="relative">
                            <label for="name" class="sr-only">Full Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-white/60"></i>
                                </div>
                                <input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    autocomplete="name" 
                                    required 
                                    class="input-glow appearance-none relative block w-full pl-10 pr-3 py-3 border-0 placeholder-white/60 text-white rounded-lg bg-white/10 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition-all duration-300 border border-white/30" 
                                    placeholder="Full Name"
                                    value="{{ old('name') }}"
                                >
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="relative">
                            <label for="email" class="sr-only">Email address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-white/60"></i>
                                </div>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    autocomplete="email" 
                                    required 
                                    class="input-glow appearance-none relative block w-full pl-10 pr-3 py-3 border-0 placeholder-white/60 text-white rounded-lg bg-white/10 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition-all duration-300 border border-white/30" 
                                    placeholder="Email address"
                                    value="{{ old('email') }}"
                                >
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="relative">
                            <label for="password" class="sr-only">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-white/60"></i>
                                </div>
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    autocomplete="new-password" 
                                    required 
                                    class="input-glow appearance-none relative block w-full pl-10 pr-10 py-3 border-0 placeholder-white/60 text-white rounded-lg bg-white/10 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition-all duration-300 border border-white/30" 
                                    placeholder="Password"
                                >
                                <button 
                                    type="button" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    onclick="togglePassword('password')"
                                >
                                    <i id="password-toggle" class="fas fa-eye text-white/60 hover:text-white/80 transition-colors cursor-pointer"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="relative">
                            <label for="password_confirmation" class="sr-only">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-white/60"></i>
                                </div>
                                <input 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    type="password" 
                                    autocomplete="new-password" 
                                    required 
                                    class="input-glow appearance-none relative block w-full pl-10 pr-10 py-3 border-0 placeholder-white/60 text-white rounded-lg bg-white/10 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition-all duration-300 border border-white/30" 
                                    placeholder="Confirm Password"
                                >
                                <button 
                                    type="button" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    onclick="togglePassword('password_confirmation')"
                                >
                                    <i id="password_confirmation-toggle" class="fas fa-eye text-white/60 hover:text-white/80 transition-colors cursor-pointer"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- User Type Selection -->
                        <div class="space-y-4">
                            <label class="block text-white/90 text-sm font-medium">Daftar sebagai:</label>
                            <div class="space-y-3">
                                <!-- Photographer Option -->
                                <div class="user-type-card rounded-xl p-4 border border-white/30 cursor-pointer" onclick="selectUserType('photographer')">
                                    <div class="flex items-start space-x-3">
                                        <input 
                                            id="photographer" 
                                            name="user_type" 
                                            type="radio" 
                                            value="photographer" 
                                            class="radio-glow mt-1 h-4 w-4 text-white/60 focus:ring-white/50 border-white/30 bg-white/10" 
                                            required
                                        >
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-camera text-white/80"></i>
                                                <label for="photographer" class="text-white font-medium cursor-pointer">
                                                    Fotografer
                                                </label>
                                            </div>
                                            <p class="text-white/70 text-sm mt-1">
                                                Saya ingin menawarkan jasa fotografi profesional
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Option -->
                                <div class="user-type-card rounded-xl p-4 border border-white/30 cursor-pointer" onclick="selectUserType('customer')">
                                    <div class="flex items-start space-x-3">
                                        <input 
                                            id="customer" 
                                            name="user_type" 
                                            type="radio" 
                                            value="customer" 
                                            class="radio-glow mt-1 h-4 w-4 text-white/60 focus:ring-white/50 border-white/30 bg-white/10" 
                                            required
                                        >
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-user-friends text-white/80"></i>
                                                <label for="customer" class="text-white font-medium cursor-pointer">
                                                    Customer
                                                </label>
                                            </div>
                                            <p class="text-white/70 text-sm mt-1">
                                                Saya ingin mencari dan memesan jasa fotografi
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('user_type')
                                <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="bg-red-500/20 border border-red-400/30 text-red-200 px-4 py-3 rounded-lg backdrop-blur-sm">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit" 
                                class="btn-hover group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-white/20 hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 backdrop-blur-sm transition-all duration-300"
                            >
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-user-plus text-white/60 group-hover:text-white/80"></i>
                                </span>
                                Daftar Sekarang
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-white/20"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-transparent text-white/60">Or continue with</span>
                            </div>
                        </div>

                        <!-- Social Register -->
                        <div class="grid grid-cols-2 gap-3">
                            <button 
                                type="button" 
                                class="group relative w-full flex justify-center py-2 px-4 border border-white/30 text-sm font-medium rounded-lg text-white bg-white/10 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 backdrop-blur-sm transition-all duration-300"
                            >
                                <i class="fab fa-google text-white/80 group-hover:text-white"></i>
                            </button>
                            <button 
                                type="button" 
                                class="group relative w-full flex justify-center py-2 px-4 border border-white/30 text-sm font-medium rounded-lg text-white bg-white/10 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 backdrop-blur-sm transition-all duration-300"
                            >
                                <i class="fab fa-facebook-f text-white/80 group-hover:text-white"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sign In Link -->
                <div class="text-center">
                    <p class="text-white/80">
                        Sudah punya akun? 
                        <a href="{{ route('user.login') }}" class="font-medium text-white hover:text-white/80 transition-colors underline decoration-white/50 hover:decoration-white">
                            Login sekarang
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-black/20 to-transparent"></div>
    
    <!-- Animated Background Elements -->
    <div class="absolute top-20 left-10 w-32 h-32 bg-white/5 rounded-full animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-24 h-24 bg-white/5 rounded-full animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 left-20 w-16 h-16 bg-white/5 rounded-full animate-pulse" style="animation-delay: 4s;"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle functionality
        window.togglePassword = function(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        };

        // User type selection functionality
        window.selectUserType = function(type) {
            const cards = document.querySelectorAll('.user-type-card');
            const radios = document.querySelectorAll('input[name="user_type"]');
            
            // Remove selected class from all cards
            cards.forEach(card => card.classList.remove('selected'));
            
            // Add selected class to clicked card
            const selectedCard = document.querySelector(`#${type}`).closest('.user-type-card');
            selectedCard.classList.add('selected');
            
            // Check the corresponding radio button
            document.getElementById(type).checked = true;
        };

        // Add focus animations to input fields
        const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Add click effect to buttons
        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Create ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add parallax effect
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.floating-shapes');
            if (parallax) {
                const speed = scrolled * 0.3;
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });

        // Form validation with smooth animations
        const form = document.querySelector('form');
        const requiredInputs = form.querySelectorAll('input[required]');

        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset previous error states
            requiredInputs.forEach(input => {
                input.classList.remove('border-red-500');
            });

            // Validate required fields
            requiredInputs.forEach(input => {
                if (!input.value || (input.type === 'email' && !input.value.includes('@'))) {
                    input.classList.add('border-red-500');
                    input.style.animation = 'shake 0.5s ease-in-out';
                    isValid = false;
                }
            });

            // Check password confirmation
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            if (password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('border-red-500');
                passwordConfirm.style.animation = 'shake 0.5s ease-in-out';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                // Remove shake animation after it completes
                setTimeout(() => {
                    requiredInputs.forEach(input => {
                        input.style.animation = '';
                    });
                }, 500);
            }
        });

        // Auto-hide error messages after 5 seconds
        const errorMessages = document.querySelectorAll('.bg-red-500\\/20');
        errorMessages.forEach(error => {
            setTimeout(() => {
                error.style.opacity = '0';
                error.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    error.remove();
                }, 300);
            }, 5000);
        });

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Visual feedback based on strength
            this.classList.remove('border-red-400', 'border-yellow-400', 'border-green-400');
            if (strength < 2) {
                this.classList.add('border-red-400');
            } else if (strength < 3) {
                this.classList.add('border-yellow-400');
            } else {
                this.classList.add('border-green-400');
            }
        });
    });
</script>
@endsection