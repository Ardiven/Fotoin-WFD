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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-gradient-secondary {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar-glass {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.08);
        }
        .content-glass {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.05);
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            max-height: 100vh;
            overflow: hidden;
        }
        .chat-container {
            height: 400px;
            overflow-y: auto;
        }
        .message-bubble {
            max-width: 80%;
            word-wrap: break-word;
        }
        .stats-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        }
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        /* Fixed viewport layout styles */
        .dashboard-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-layout {
            flex: 1;
            display: flex;
            overflow: hidden;
            min-height: 0;
        }
        
        .content-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
        }
        
        .sidebar-container {
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
        }
        
        /* Custom scrollbar styling */
        .content-area::-webkit-scrollbar,
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }
        
        .content-area::-webkit-scrollbar-track,
        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        
        .content-area::-webkit-scrollbar-thumb,
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .content-area::-webkit-scrollbar-thumb:hover,
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased">

    <div x-data="dashboardApp()" class="dashboard-container">
        <!-- Header -->
        <header class="glass-effect border-b border-white/20 sticky top-0 z-50 flex-shrink-0">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-white mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-camera text-white text-lg"></i>
                        </div>
                        <h1 class="text-white text-xl font-bold">FotoIn Dashboard</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4 block md:block lg:hidden">
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-white/80 hover:text-white">
                            <img src="{{ asset("storage/" . Auth::user()->profile_photo)}}" alt="" class="w-10 h-10 rounded-full">
                            <span>{{Auth::user()->name}}</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        
                        <div x-show="userMenuOpen" @click.away="userMenuOpen = false" 
                             x-transition
                             class="absolute right-0 mt-2 w-48 glass-effect border border-white/20 rounded-lg shadow-lg py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Layout -->
        <div class="main-layout">
            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
                class="fixed lg:relative lg:translate-x-0 w-64 sidebar-container sidebar-glass border-r border-white/20 transition-transform z-40">
                
                <!-- Navigation -->
                <nav class="sidebar-nav px-4 py-6">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{route('admin.index')}}" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all hover:bg-white/20 text-white">
                                <i class="fas fa-home mr-3"></i>Overview
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.users')}}" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-user mr-3"></i>User
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.city.index')}}" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-calendar mr-3"></i>City
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.specialties.index')}}" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-money-bill mr-3"></i>
                                Specialty
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <!-- User Section -->
                <div class="px-4 py-4 border-t border-white/20 flex-shrink-0">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-primary rounded-full flex items-center justify-center mr-3">
                            <img src="{{ asset("storage/" . Auth::user()->profile_photo)}}" alt="" class="w-10 h-10 rounded-full">
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-sm font-medium">{{Auth::user()->name}}</p>
                            <p class="text-white/60 text-xs">Photographer</p>
                        </div>
                    <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-white/80 hover:text-white transition-colors p-2">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <main class="content-area bg-white/5">
                <!-- Demo content to show scrolling behavior -->
                <div class="py-8 px-4">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/50 z-30 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>
    </div>

    <script>
        function dashboardApp() {
            return {
                activeTab: 'overview',
                sidebarOpen: false,
                userMenuOpen: false,
                showNotifications: false,
                unreadMessages: 3,
                portfolioCategory: 'all',
                selectedChat: 'sarah',
                showAddPackage: false,
                
                init() {
                    // Initialize any data or event listeners here
                    console.log('Dashboard initialized');
                },
                
                logout() {
                    if (confirm('Are you sure you want to logout?')) {
                        // Implement logout functionality
                        window.location.href = '/login';
                    }
                }
            }
        }
        
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // Implement logout functionality
                window.location.href = '/login';
            }
        }
    </script>
    @stack('scripts')
</body>
</html>