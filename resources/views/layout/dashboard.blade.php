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
                <div class="flex items-center space-x-4">
                    <button @click="showNotifications = !showNotifications" class="relative text-white/80 hover:text-white">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                    </button>
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-white/80 hover:text-white">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' fill='%23667eea'/%3E%3Ctext x='16' y='20' text-anchor='middle' fill='white' font-family='Arial' font-size='14'%3EJS%3C/text%3E%3C/svg%3E" 
                                 class="w-8 h-8 rounded-full mr-2" alt="Profile">
                            <span>{{Auth::user()->name}}</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        
                        <div x-show="userMenuOpen" @click.away="userMenuOpen = false" 
                             x-transition
                             class="absolute right-0 mt-2 w-48 glass-effect border border-white/20 rounded-lg shadow-lg py-2">
                            <a href="#" class="flex items-center px-4 py-2 text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-user mr-3"></i>Profile
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-cog mr-3"></i>Settings
                            </a>
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

            <!-- Notifications Dropdown -->
            <div x-show="showNotifications" @click.away="showNotifications = false" 
                 x-transition
                 class="absolute right-6 top-16 w-80 glass-effect border border-white/20 rounded-lg shadow-lg py-2">
                <div class="px-4 py-2 border-b border-white/20">
                    <h3 class="text-white font-semibold">Notifications</h3>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <div class="px-4 py-3 text-white/80 border-b border-white/10">
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-blue-400 mr-2"></i>
                            <span class="text-sm">New booking request from Sarah Johnson</span>
                        </div>
                        <span class="text-xs text-white/60">2 hours ago</span>
                    </div>
                    <div class="px-4 py-3 text-white/80 border-b border-white/10">
                        <div class="flex items-center">
                            <i class="fas fa-message text-green-400 mr-2"></i>
                            <span class="text-sm">New message from client</span>
                        </div>
                        <span class="text-xs text-white/60">5 hours ago</span>
                    </div>
                    <div class="px-4 py-3 text-white/80">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            <span class="text-sm">New review received</span>
                        </div>
                        <span class="text-xs text-white/60">1 day ago</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Layout -->
        <div class="main-layout">
            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
                class="fixed lg:relative lg:translate-x-0 w-64 sidebar-container sidebar-glass border-r border-white/20 transition-transform z-40">
                
                <!-- Logo Section -->
                <div class="flex items-center px-6 py-4 border-b border-white/20 flex-shrink-0">
                    <div class="w-8 h-8 bg-gradient-primary rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-camera text-white"></i>
                    </div>
                    <span class="text-white text-xl font-bold">FotoIn</span>
                </div>
                
                <!-- Navigation -->
                <nav class="sidebar-nav px-4 py-6">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{route('photographer.overview')}}" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all hover:bg-white/20 text-white">
                                <i class="fas fa-home mr-3"></i>Overview
                            </a>
                        </li>
                        <li>
                            <a href="{{route('photographer.profile')}}" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-user mr-3"></i>Profile Data
                            </a>
                        </li>
                        <li>
                            <a href="{{route('photographer.packages.index')}}" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-box mr-3"></i>Packages
                            </a>
                        </li>
                        <li>
                            <a href="#" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-calendar mr-3"></i>Bookings
                            </a>
                        </li>
                        <li>
                            <a href="#" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-comments mr-3"></i>
                                Chat
                                <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    3
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('photographer.portfolio.index')}}" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-images mr-3"></i>Portfolio
                            </a>
                        </li>
                        <li>
                            <a href="#" 
                            class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-white/80 hover:text-white hover:bg-white/10">
                                <i class="fas fa-chart-bar mr-3"></i>Analytics
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <!-- User Section -->
                <div class="px-4 py-4 border-t border-white/20 flex-shrink-0">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-primary rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-sm">JS</span>
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
</body>
</html>