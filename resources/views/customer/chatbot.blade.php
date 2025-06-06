<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi AI Fotografer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
        }
        
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        secondary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12'
                        }
                    },
                    animation: {
                        'bounce-slow': 'bounce 2s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        .category-overlay {
            transition: all 0.3s ease;
        }
        .category-overlay:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
    </style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
        
        .chat-bubble-ai {
            border-top-left-radius: 0;
        }
        
        .chat-bubble-user {
            border-bottom-right-radius: 0;
        }
        
        .gradient-button {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            transition: all 0.3s ease;
        }
        
        .gradient-button:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('index') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('storage/icon/image.png') }}" alt="Logo" class="h-10 w-auto">
                        <span class="text-2xl font-bold">
                            <span class="text-primary-700">Foto</span><span class="text-secondary-500">in</span>
                        </span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-2">
                    <input type="text" placeholder="Search products..." 
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-400 transition-all">
                    
                    <button onclick="toggleDarkMode()" 
                        class="p-2 rounded-lg bg-primary-500 text-white hover:bg-primary-600 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    <a href="{{ route('chat') }}" class="pl-2 rounded-lg text-white hover:scale-125 transition-all duration-300">
                        <svg class ="h-7 w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="200" height="200"> <path d="M60 10 C85 10, 105 30, 105 55 C105 80, 85 100, 60 100 C55 100, 50 99, 45 97 L20 110 L30 85 C15 75, 15 60, 15 55 C15 30, 35 10, 60 10Z"fill="white"stroke="black"stroke-width="5"/><circle cx="45" cy="55" r="4" fill="black" /><circle cx="60" cy="55" r="4" fill="black" /><circle cx="75" cy="55" r="4" fill="black"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>


    <!-- Main Section -->
    <main class="container mx-auto mt-8 px-4 pb-16">
        <!-- Hero Section -->
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Konsultasi Dengan AI</h2>
            <p class="text-gray-600">Jelaskan kebutuhan fotografi kamu, dan biarkan AI kami bantu temukan fotografer yang paling cocok.</p>
        </div>
        
        <!-- Chat Section -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-robot text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">Asisten AI Fotografer</h2>
                            <p class="text-sm text-green-600">Online - Siap Membantu</p>
                        </div>
                    </div>
                </div>
                
                <!-- Chatbox UI -->
                <div class="border rounded-xl p-4 h-96 overflow-y-auto bg-gray-50 mb-4" id="chat-box">
                    <!-- Welcome message -->
                    <div class="flex justify-center my-4">
                        <span class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">Hari ini</span>
                    </div>
                    
                    <!-- AI messages -->
                    <div class="mb-4">
                        <div class="flex items-start">
                            <div class="bg-blue-100 h-8 w-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-robot text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 ml-1 mb-1">AI Asisten</div>
                                <div class="bg-blue-100 text-gray-800 p-3 rounded-xl chat-bubble-ai inline-block max-w-xs md:max-w-md">
                                    Halo! Selamat datang di FotoFinder. Saya adalah asisten AI yang siap membantu kamu menemukan fotografer yang tepat. Kamu mencari fotografer untuk acara apa?
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User messages -->
                    <div class="mb-4 text-right">
                        <div class="flex items-start justify-end">
                            <div>
                                <div class="text-xs text-gray-500 mr-1 mb-1">Kamu</div>
                                <div class="bg-blue-600 text-white p-3 rounded-xl chat-bubble-user inline-block max-w-xs md:max-w-md">
                                    Untuk prewedding di Bali bulan Juli
                                </div>
                            </div>
                            <div class="bg-gray-200 h-8 w-8 rounded-full flex items-center justify-center ml-2">
                                <i class="fas fa-user text-gray-500 text-sm"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- AI response -->
                    <div class="mb-4">
                        <div class="flex items-start">
                            <div class="bg-blue-100 h-8 w-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-robot text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 ml-1 mb-1">AI Asisten</div>
                                <div class="bg-blue-100 text-gray-800 p-3 rounded-xl chat-bubble-ai inline-block max-w-xs md:max-w-md">
                                    Bagus sekali! Bali adalah lokasi yang populer untuk prewedding. Apakah kamu sudah memiliki konsep atau gaya fotografi tertentu yang kamu inginkan? Misalnya, gaya natural/candid, artistic, dramatic, atau vintage?
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Typing indicator -->
                    <div class="flex items-center">
                        <div class="bg-blue-100 h-8 w-8 rounded-full flex items-center justify-center mr-2">
                            <i class="fas fa-robot text-blue-600 text-sm"></i>
                        </div>
                        <div class="bg-gray-100 px-4 py-2 rounded-xl flex items-center">
                            <div class="typing-animation flex space-x-1">
                                <div class="h-2 w-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                                <div class="h-2 w-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                <div class="h-2 w-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Chat -->
                <form class="flex mt-4 gap-2" onsubmit="event.preventDefault();">
                    <button type="button" class="text-gray-400 hover:text-gray-600 p-2">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <input type="text" placeholder="Tulis pesan..." class="flex-1 p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50">
                    <button type="submit" class="gradient-button text-white px-4 py-2 rounded-xl flex items-center gap-2">
                        <span>Kirim</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Rekomendasi Fotografer -->
        <div class="mt-16">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-semibold text-gray-800">Rekomendasi Fotografer</h3>
                <a href="#" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <i class="fas fa-chevron-right text-sm"></i>
                </a>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Photographer Card 1 -->
                <div class="bg-white p-5 rounded-2xl shadow-md card-hover">
                    <div class="relative mb-4">
                        <img src="https://i.pinimg.com/736x/8f/04/20/8f042044eab64de23ad11d8eac03525e.jpg" alt="Andi Wijaya Portfolio" class="rounded-xl w-full h-52 object-cover">
                        <span class="absolute top-3 left-3 bg-blue-600 text-white text-xs px-2 py-1 rounded-lg">Top Rated</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-lg font-semibold">Andi Wijaya</h4>
                            <p class="text-gray-600 text-sm">Spesialis prewedding & candid</p>
                        </div>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 mr-1 text-sm"></i>
                            <span class="text-sm font-medium">4.9</span>
                        </div>
                    </div>
                    <div class="flex items-center mt-3 mb-4">
                        <i class="fas fa-map-marker-alt text-red-500 mr-1 text-sm"></i>
                        <span class="text-gray-600 text-sm">Bali</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <i class="fas fa-camera text-gray-500 mr-1 text-sm"></i>
                        <span class="text-gray-600 text-sm">5+ tahun pengalaman</span>
                    </div>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-lg">Prewedding</span>
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-lg">Candid</span>
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-lg">Natural</span>
                    </div>
                    <button class="w-full gradient-button text-white px-4 py-2 rounded-xl flex items-center justify-center gap-1">
                        <i class="far fa-eye"></i>
                        <span>Lihat Profil</span>
                    </button>
                </div>
                
                <!-- Photographer Card 2 -->
                <div class="bg-white p-5 rounded-2xl shadow-md card-hover">
                    <div class="relative mb-4">
                        <img src="https://i.pinimg.com/736x/45/08/c5/4508c54f1120727c661647b6ee4903cb.jpg" alt="Maya Sari Portfolio" class="rounded-xl w-full h-52 object-cover">
                        <span class="absolute top-3 left-3 bg-green-600 text-white text-xs px-2 py-1 rounded-lg">Tersedia</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-lg font-semibold">Maya Sari</h4>
                            <p class="text-gray-600 text-sm">Prewedding & wedding dokumentasi</p>
                        </div>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 mr-1 text-sm"></i>
                            <span class="text-sm font-medium">4.8</span>
                        </div>
                    </div>
                    <div class="flex items-center mt-3 mb-4">
                        <i class="fas fa-map-marker-alt text-red-500 mr-1 text-sm"></i>
                        <span class="text-gray-600 text-sm">Bali</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <i class="fas fa-camera text-gray-500 mr-1 text-sm"></i>
                        <span class="text-gray-600 text-sm">7+ tahun pengalaman</span>
                    </div>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-lg">Wedding</span>
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-lg">Prewedding</span>
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-lg">Artistic</span>
                    </div>
                    <button class="w-full gradient-button text-white px-4 py-2 rounded-xl flex items-center justify-center gap-1">
                        <i class="far fa-eye"></i>
                        <span>Lihat Profil</span>
                    </button>
                </div>
                
                <!-- Photographer Card 3 -->
                <div class="bg-white p-5 rounded-2xl shadow-md card-hover">
                    <div class="relative mb-4">
                        <img src="https://i.pinimg.com/736x/ca/67/2e/ca672ef62b9957fa05f3f197960f326a.jpg" alt="Budi Santoso Portfolio" class="rounded-xl w-full h-52 object-cover">
                        <span class="absolute top-3 left-3 bg-purple-600 text-white text-xs px-2 py-1 rounded-lg">Premium</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-lg font-semibold">Budi Santoso</h4>
                            <p class="text-gray-600 text-sm">Landscape & cinematic prewedding</p>
                        </div>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 mr-1 text-sm"></i>
                            <span class="text-sm font-medium">5.0</span>
                        </div>
                    </div>
                    <div class="flex items-center mt-3 mb-4">
                        <i class="fas fa-map-marker-alt text-red-500 mr-1 text-sm"></i>
                        <span class="text-gray-600 text-sm">Bali, Jakarta</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <i class="fas fa-camera text-gray-500 mr-1 text-sm"></i>
                        <span class="text-gray-600 text-sm">10+ tahun pengalaman</span>
                    </div>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-lg">Cinematic</span>
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-lg">Dramatic</span>
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-lg">Landscape</span>
                    </div>
                    <button class="w-full gradient-button text-white px-4 py-2 rounded-xl flex items-center justify-center gap-1">
                        <i class="far fa-eye"></i>
                        <span>Lihat Profil</span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-16 bg-white py-6 text-center text-gray-500">
        &copy; 2025 FotoFinder. Semua Hak Dilindungi.
    </footer>

    <!-- Mobile Menu (Hidden by default) -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden" id="mobile-menu-overlay">
        <div class="bg-white h-full w-4/5 max-w-xs p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-camera text-blue-600 text-xl"></i>
                    <h1 class="text-xl font-bold text-gray-800">FotoFinder</h1>
                </div>
                <button id="close-mobile-menu" class="text-gray-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav>
                <ul class="space-y-4">
                    <li><a href="#" class="block py-2 px-4 font-medium hover:bg-blue-50 hover:text-blue-600 rounded-lg">Home</a></li>
                    <li><a href="#" class="block py-2 px-4 font-medium hover:bg-blue-50 hover:text-blue-600 rounded-lg">Fotografer</a></li>
                    <li><a href="#" class="block py-2 px-4 font-medium bg-blue-50 text-blue-600 rounded-lg">Konsultasi AI</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.querySelector('.md\\:hidden button');
            const closeButton = document.getElementById('close-mobile-menu');
            const mobileMenu = document.getElementById('mobile-menu-overlay');
            
            if (menuButton && closeButton && mobileMenu) {
                menuButton.addEventListener('click', function() {
                    mobileMenu.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
                
                closeButton.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            }
            
            // Simulated Chat Send
            const chatForm = document.querySelector('form');
            const chatInput = document.querySelector('input[placeholder="Tulis pesan..."]');
            const chatBox = document.getElementById('chat-box');
            
            if (chatForm && chatInput && chatBox) {
                chatForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (chatInput.value.trim() !== '') {
                        // Create user message
                        const userMessage = document.createElement('div');
                        userMessage.className = 'mb-4 text-right';
                        userMessage.innerHTML = `
                            <div class="flex items-start justify-end">
                                <div>
                                    <div class="text-xs text-gray-500 mr-1 mb-1">Kamu</div>
                                    <div class="bg-blue-600 text-white p-3 rounded-xl chat-bubble-user inline-block max-w-xs md:max-w-md">
                                        ${chatInput.value}
                                    </div>
                                </div>
                                <div class="bg-gray-200 h-8 w-8 rounded-full flex items-center justify-center ml-2">
                                    <i class="fas fa-user text-gray-500 text-sm"></i>
                                </div>
                            </div>
                        `;
                        
                        chatBox.appendChild(userMessage);
                        chatInput.value = '';
                        
                        // Auto-scroll to bottom
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                });
            }
        });
    </script>
</body>
</html>