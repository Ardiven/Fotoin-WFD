<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FotoIn') }} - @yield('title', 'Professional Photography Platform')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Base styles for layout */
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
        
        /* Page background */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
    <!-- Navigation -->
    <nav class="glass-effect border-b border-white/20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="inline-flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-camera text-white text-lg"></i>
                        </div>
                        <a href="{{ url('/') }}" class="text-white text-xl font-bold">FotoIn</a>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-white/80 hover:text-white transition-colors font-semibold">Home</a>
                    <a href="{{ route('customer.photographers')}}" class="text-white/80 hover:text-white transition-colors">Photographers</a>
                    <a href="{{ route('customer.payment.index') }}" class="text-white/80 hover:text-white transition-colors">Payments</a>
                    <a href="{{ route('chat') }}" class="text-white/80 hover:text-white transition-colors">Contact</a>
                    
                    @guest
                        <div class="flex space-x-2">
                            <a href="{{ route('login') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300 border border-white/30">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="bg-white text-purple-600 hover:bg-white/90 px-4 py-2 rounded-lg transition-all duration-300 font-semibold">
                                Sign Up
                            </a>
                        </div>
                    @else
                        <!-- User Dropdown -->
                        <div class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-white/80 hover:text-white transition-colors">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs transition-transform" :class="{ 'rotate-180': userMenuOpen }"></i>
                            </button>
                            
                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 glass-effect border border-white/20 rounded-lg shadow-lg py-2">
                                <a href="{{ url('/profile') }}" class="flex items-center px-4 py-2 text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                                    <i class="fas fa-user mr-3"></i>
                                    Profile
                                </a>
                                <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-2 text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                                    <i class="fas fa-tachometer-alt mr-3"></i>
                                    Dashboard
                                </a>
                                <a href="{{ url('/bookings') }}" class="flex items-center px-4 py-2 text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                                    <i class="fas fa-calendar mr-3"></i>
                                    My Bookings
                                </a>
                                <div class="border-t border-white/20 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white/80 hover:text-white">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 glass-effect border-t border-white/20">
                    <a href="{{ url('/') }}" class="block px-3 py-2 text-white/80 hover:text-white transition-colors">Home</a>
                    <a href="{{ url('/photographers') }}" class="block px-3 py-2 text-white/80 hover:text-white transition-colors">Photographers</a>
                    <a href="{{ url('/categories') }}" class="block px-3 py-2 text-white/80 hover:text-white transition-colors">Categories</a>
                    <a href="{{ url('/contact') }}" class="block px-3 py-2 text-white/80 hover:text-white transition-colors">Contact</a>
                    
                    @guest
                        <div class="border-t border-white/20 pt-3 mt-3">
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-white/80 hover:text-white transition-colors">Sign In</a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 text-white/80 hover:text-white transition-colors">Sign Up</a>
                        </div>
                    @else
                        <div class="border-t border-white/20 pt-3 mt-3">
                            <div class="px-3 py-2 text-white font-semibold">{{ Auth::user()->name }}</div>
                            <a href="{{ url('/profile') }}" class="block px-3 py-2 text-white/80 hover:text-white transition-colors">Profile</a>
                            <a href="{{ url('/dashboard') }}" class="block px-3 py-2 text-white/80 hover:text-white transition-colors">Dashboard</a>
                            <a href="{{ url('/bookings') }}" class="block px-3 py-2 text-white/80 hover:text-white transition-colors">My Bookings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-white/80 hover:text-white transition-colors">Logout</button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <span onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <span onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <span onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Alpine.js for interactive components -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Global JavaScript -->
    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                if (alert.style.display !== 'none') {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>