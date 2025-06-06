@extends('layout.app')

@section('title', 'Chat')

@push('styles')
<style>
    .chat-container {
        height: calc(100vh - 64px); /* Account for navbar height */
    }
    .messages-area {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
    }
    .messages-area::-webkit-scrollbar {
        width: 6px;
    }
    .messages-area::-webkit-scrollbar-track {
        background: transparent;
    }
    .messages-area::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }
    .messages-area::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>
@endpush

@section('content')
<div class="flex flex-1 overflow-hidden chat-container" x-data="{ packageModalOpen: false }">
    <!-- Sidebar daftar pengguna -->
    <aside class="w-1/4 glass-effect border-r border-white/20 overflow-y-auto">
        <div class="p-4 font-bold text-lg border-b border-white/20 flex items-center justify-between">
            <span class="text-white">Chat Users</span>
            <button class="text-yellow-300 hover:text-yellow-200 text-sm font-normal transition-colors">
                <i class="fas fa-plus mr-1"></i>New
            </button>
        </div>
        
        <div class="p-3">
            <div class="relative">
                <input type="text" placeholder="Search chats..." 
                    class="w-full pl-9 pr-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-300/50 focus:border-yellow-300/50">
                <i class="fas fa-search text-white/60 absolute left-3 top-2.5 text-sm"></i>
            </div>
        </div>
        
        <ul class="divide-y divide-white/10">
            <li class="p-4 bg-white/20 border-l-4 border-yellow-300 cursor-pointer flex items-center space-x-3 hover:bg-white/25 transition-all">
                <div class="w-10 h-10 rounded-full bg-white/20 flex-shrink-0 relative">
                    <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-yellow-200">Jonathan</span>
                        <span class="text-xs text-white/60">3m ago</span>
                    </div>
                    <p class="text-sm text-white/70 truncate">Lagi belajar buat aplikasi chat...</p>
                </div>
            </li>
            
            <li class="p-4 hover:bg-white/10 cursor-pointer flex items-center space-x-3 transition-all">
                <div class="w-10 h-10 rounded-full bg-white/20 flex-shrink-0">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-white">Steven</span>
                        <span class="text-xs text-white/60">1h ago</span>
                    </div>
                    <p class="text-sm text-white/70 truncate">Bagaimana dengan proyek kita?</p>
                </div>
            </li>
            
            <li class="p-4 hover:bg-white/10 cursor-pointer flex items-center space-x-3 transition-all">
                <div class="w-10 h-10 rounded-full bg-white/20 flex-shrink-0">
                    <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-white">Victor</span>
                        <span class="text-xs text-white/60">2d ago</span>
                    </div>
                    <p class="text-sm text-white/70 truncate">Terima kasih atas foto-fotonya!</p>
                </div>
            </li>
        </ul>
    </aside>

    <!-- Chat utama -->
    <div class="flex flex-col flex-1">
        <!-- Header chat -->
        <header class="p-4 glass-effect border-b border-white/20 font-medium shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-white/20">
                    <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                </div>
                <div>
                    <div class="font-medium text-white">Jonathan</div>
                    <div class="text-xs text-green-300 flex items-center">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div>
                        Online
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button class="text-white/70 hover:text-yellow-300 transition-colors p-2 rounded-full hover:bg-white/10">
                    <i class="fas fa-phone text-lg"></i>
                </button>
                <button class="text-white/70 hover:text-yellow-300 transition-colors p-2 rounded-full hover:bg-white/10">
                    <i class="fas fa-video text-lg"></i>
                </button>
                <button class="text-white/70 hover:text-yellow-300 transition-colors p-2 rounded-full hover:bg-white/10">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
        </header>

        <!-- Area pesan -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4 messages-area">
            <!-- Pesan masuk -->
            <div class="flex items-start space-x-2 max-w-xs">
                <div class="w-8 h-8 rounded-full bg-white/20 flex-shrink-0">
                    <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                </div>
                <div>
                    <div class="glass-effect p-3 rounded-lg shadow-sm border border-white/20">
                        <p class="text-white">Halo, bro. Gimana kabarnya?</p>
                    </div>
                    <div class="text-xs text-white/60 mt-1 ml-1">9:32 AM</div>
                </div>
            </div>
            
            <!-- Pesan keluar -->
            <div class="flex items-start justify-end space-x-2">
                <div class="flex flex-col items-end">
                    <div class="bg-yellow-500 text-purple-900 p-3 rounded-lg shadow-sm max-w-xs font-medium">
                        <p>Baik, bro. Kamu gimana?</p>
                    </div>
                    <div class="text-xs text-white/60 mt-1 mr-1">9:33 AM</div>
                </div>
            </div>
            
            <!-- Pesan masuk -->
            <div class="flex items-start space-x-2 max-w-xs">
                <div class="w-8 h-8 rounded-full bg-white/20 flex-shrink-0">
                    <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                </div>
                <div>
                    <div class="glass-effect p-3 rounded-lg shadow-sm border border-white/20">
                        <p class="text-white">Baik juga, lagi ngapain?</p>
                    </div>
                    <div class="text-xs text-white/60 mt-1 ml-1">9:34 AM</div>
                </div>
            </div>
            
            <div class="flex items-start justify-end space-x-2">
                <div class="flex flex-col items-end">
                    <div class="bg-yellow-500 text-purple-900 p-3 rounded-lg shadow-sm max-w-xs font-medium">
                        <p>Lagi belajar buat aplikasi chat pakai Laravel dan Tailwind</p>
                    </div>
                    <div class="text-xs text-white/60 mt-1 mr-1">9:35 AM</div>
                </div>
            </div>
            
            <div class="flex items-start space-x-2 max-w-xs">
                <div class="w-8 h-8 rounded-full bg-white/20 flex-shrink-0">
                    <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                </div>
                <div>
                    <div class="glass-effect p-3 rounded-lg shadow-sm border border-white/20">
                        <p class="text-white">Wah keren banget, jadi sudah bisa realtime ya?</p>
                    </div>
                    <div class="text-xs text-white/60 mt-1 ml-1">9:36 AM</div>
                </div>
            </div>
            
            <!-- Custom Package Message -->
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 rounded-full bg-white/20 flex-shrink-0">
                    <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                </div>
                <div>
                    <div class="glass-effect p-4 rounded-lg shadow-lg max-w-md border border-white/20">
                        <div class="font-medium text-lg text-yellow-300 mb-3 flex items-center space-x-2">
                            <i class="fas fa-camera text-yellow-300"></i>
                            <span>Custom Photography Package</span>
                        </div>
                        <div class="space-y-2 text-white">
                            <div class="flex justify-between">
                                <span class="text-white/80">Category:</span>
                                <span class="font-medium">Wedding</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/80">Duration:</span>
                                <span class="font-medium">3 hours</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/80">Price:</span>
                                <span class="font-medium text-yellow-300">₹299.99</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/80">Valid Until:</span>
                                <span class="font-medium">June 30, 2025</span>
                            </div>
                            <div class="mt-3">
                                <span class="text-white/80 block mb-1">Description:</span>
                                <p class="text-sm text-white/90">Complete wedding photography package including pre-wedding shots, ceremony coverage, and reception. Includes 100 edited photos.</p>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button class="bg-yellow-500 hover:bg-yellow-400 text-purple-900 px-4 py-2 rounded-lg text-sm font-semibold transition-all transform hover:scale-105">
                                    Accept Offer
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-xs text-white/60 mt-1 ml-1">9:38 AM</div>
                </div>
            </div>
            
            <div class="flex items-start justify-end space-x-2">
                <div class="flex flex-col items-end">
                    <div class="bg-yellow-500 text-purple-900 p-3 rounded-lg shadow-sm max-w-xs font-medium">
                        <p>Iya, pakai Laravel Echo dan Pusher untuk komunikasi realtime</p>
                    </div>
                    <div class="text-xs text-white/60 mt-1 mr-1">9:40 AM</div>
                </div>
            </div>
        </div>

        <!-- Form input -->
        <form class="p-4 glass-effect border-t border-white/20 flex items-center space-x-3">
            <button type="button" class="p-2 rounded-full text-white/70 hover:text-yellow-300 hover:bg-white/10 transition-all">
                <i class="fas fa-paperclip text-lg"></i>
            </button>
            <input
                type="text"
                placeholder="Ketik pesan..."
                class="flex-1 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-yellow-300/50 focus:border-yellow-300/50 text-sm"
            />
            <button 
                type="button" 
                @click="packageModalOpen = true"
                class="p-2 rounded-full text-white/70 hover:text-yellow-300 hover:bg-white/10 transition-all"
                title="Send Custom Package"
            >
                <i class="fas fa-gift text-lg"></i>
            </button>
            <button
                type="submit"
                class="bg-yellow-500 text-purple-900 px-4 py-2 rounded-full hover:bg-yellow-400 transition-all flex items-center space-x-2 font-semibold transform hover:scale-105"
            >
                <span>Kirim</span>
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<!-- Package Modal -->
<div x-show="packageModalOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-90"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-90"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
     style="display: none;">
    <div class="glass-effect border border-white/20 rounded-2xl shadow-2xl max-w-md w-full max-h-screen overflow-y-auto" @click.away="packageModalOpen = false">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-white flex items-center">
                    <i class="fas fa-gift mr-2 text-yellow-300"></i>
                    Send Custom Package
                </h3>
                <button @click="packageModalOpen = false" class="text-white/70 hover:text-yellow-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form>
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="category" class="text-sm font-medium text-white block mb-2">Category</label>
                        <input type="text" id="category" 
                            class="bg-white/10 border border-white/20 text-white placeholder-white/60 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-yellow-300/50 focus:border-yellow-300/50" 
                            placeholder="Wedding" required>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="duration" class="text-sm font-medium text-white block mb-2">Duration (hours)</label>
                        <input type="number" id="duration" name="duration" min="1" 
                            class="bg-white/10 border border-white/20 text-white placeholder-white/60 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-yellow-300/50 focus:border-yellow-300/50" 
                            placeholder="3" required>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="price" class="text-sm font-medium text-white block mb-2">Price</label>
                        <input type="number" id="price" 
                            class="bg-white/10 border border-white/20 text-white placeholder-white/60 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-yellow-300/50 focus:border-yellow-300/50" 
                            placeholder="299.99" required>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="valid-to" class="text-sm font-medium text-white block mb-2">Valid To</label>
                        <input type="date" id="valid-to" name="valid-to" 
                            class="bg-white/10 border border-white/20 text-white placeholder-white/60 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-yellow-300/50 focus:border-yellow-300/50">
                    </div>

                    <div class="col-span-6">
                        <label for="description" class="text-sm font-medium text-white block mb-2">Description</label>
                        <textarea id="description" rows="3" 
                            class="bg-white/10 border border-white/20 text-white placeholder-white/60 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-yellow-300/50 focus:border-yellow-300/50" 
                            placeholder="Describe your package..."></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="packageModalOpen = false" 
                        class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all text-sm font-medium border border-white/30">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="bg-yellow-500 hover:bg-yellow-400 text-purple-900 px-4 py-2 rounded-lg transition-all text-sm font-semibold transform hover:scale-105">
                        Send Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection