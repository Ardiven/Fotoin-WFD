<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Checkboxes with Filters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
</head>
<body class="bg-gray-100">
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('index') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('storage/icon/image.png') }}" alt="Logo" class="h-10 w-auto">
                        <span class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">Foto<span class="text-primary-500">in</span></span>
                    </a>
                    
                </div>
                
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Search products..." 
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-400 transition-all">
                    
                    <button onclick="toggleDarkMode()" 
                        class="p-2 rounded-lg bg-primary-500 text-white hover:bg-primary-600 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <div class="min-h-screen p-4 md:p-8">
        <form method="get" action="" class="max-w-6xl mx-auto" x-data="{
            dropdowns: [
                {
                    name: 'kota',
                    label: 'kota',
                    items: [
                        { value: 'jakarta', label: 'Jakarta' },
                        { value: 'surabaya', label: 'Surabaya' },
                        { value: 'bandung', label: 'Bandung' },
                        { value: 'medan', label: 'Medan' },
                        { value: 'semarang', label: 'Semarang' },
                        { value: 'makassar', label: 'Makassar' },
                        { value: 'palembang', label: 'Palembang' },
                        { value: 'depok', label: 'Depok' },
                        { value: 'bekasi', label: 'Bekasi' },
                        { value: 'tangerang', label: 'Tangerang' }
                    ]

                },
                {
                    name: 'category',
                    label: 'category',
                    items: [
                        { value: 'prewedding', label: 'Prewedding' },
                        { value: 'wedding', label: 'Wedding' },
                        { value: 'engagement', label: 'Engagement' },
                        { value: 'birthday', label: 'Birthday' },
                        { value: 'maternity', label: 'Maternity' },
                        { value: 'newborn', label: 'Newborn' },
                        { value: 'family', label: 'Family' },
                        { value: 'graduation', label: 'Graduation' },
                        { value: 'product', label: 'Product' },
                        { value: 'fashion', label: 'Fashion' },
                        { value: 'corporate', label: 'Corporate Event' },
                        { value: 'sports', label: 'Sports' },
                        { value: 'travel', label: 'Travel' }
                    ]

                },
                {
                    name: 'harga',
                    label: 'harga',
                    items: [
                        { value: { min: 0, max: 999999 }, label: 'Kurang dari 1jt' },
                        { value: { min: 1000000, max: 5000000 }, label: '1jt - 5jt' },
                        { value: { min: 5000001, max: 10000000 }, label: '5jt - 10jt' },
                        { value: { min: 10000001, max: null }, label: 'Di atas 10jt' }
                    ]

                }
            ],
            getUrlParams(name) {
                const params = new URLSearchParams(window.location.search);
                const values = params.getAll(name + '[]');
                return values.length > 0 ? values : [];
            },
            getSelectedItems(dropdown) {
                return this.$refs[dropdown.name] 
                    ? dropdown.items.filter(item => this.$refs[dropdown.name].selected.includes(item.value))
                    : [];
            }
        }">
            <!-- Filters container -->
            <div class="flex flex-wrap items-start gap-2 mb-4">
                <template x-for="dropdown in dropdowns" :key="dropdown.name">
                    <div x-data="{
                            open: false,
                            search: '',
                            selected: getUrlParams(dropdown.name),
                            get filteredItems() {
                                return dropdown.items.filter(item =>
                                    item.label.toLowerCase().includes(this.search.toLowerCase())
                                )
                            },
                            get selectedLabel() {
                                if (this.selected.length === 0) return dropdown.label;
                                return `${dropdown.label}: ${this.selected.length}`;
                            }
                        }" 
                        class="relative w-full md:w-auto"
                        :x-ref="dropdown.name">
                        <!-- Hidden inputs for form submission -->
                        <template x-for="value in selected" :key="value">
                            <input type="hidden" :name="dropdown.name + '[]'" :value="value" aria-label="dropdown">
                        </template>

                        <!-- Custom dropdown button -->
                        <button type="button"
                                @click="open = !open; $nextTick(() => { if(open) $refs.searchInput.focus() })"
                                class="inline-flex justify-between w-full bg-white rounded md:w-48 px-2 py-2 text-base text-stone-500 bg-gray-50 border border-stone-300 appearance-none focus:outline-none ring-0 focus:ring-2 focus:ring-primary-200 focus:border-primary-500 peer">
                            <span x-text="selectedLabel" class="truncate mx-2"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open"
                             @click.away="open = false"
                             class="absolute z-10 w-full mt-2 rounded bg-white ring-2 ring-primary-200 border border-primary-500"
                             style="display: none;">
                            <!-- Search input with clear button -->
                            <div class="relative">
                                <input x-model="search"
                                       x-ref="searchInput"
                                       @focus="$el.select()"
                                       class="block w-full px-4 py-2 text-gray-800 rounded-t border-b focus:outline-none"
                                       type="text"
                                       :placeholder="'Search for a ' + dropdown.label.toLowerCase()"
                                       @click.stop>
                                <!-- Clear button -->
                                <button type="button"
                                        @click="search = ''"
                                        class="absolute inset-y-0 right-2 px-2 flex items-center"
                                        x-show="search.length > 0">
                                    <svg class="h-4 w-4 text-gray-400 hover:text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Dropdown items -->
                            <div class="rounded-b max-h-60 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-primary-100 [&::-webkit-scrollbar-thumb]:bg-primary-300">
                                <template x-for="item in filteredItems" :key="item.value">
                                    <div @click="selected.includes(item.value) ? selected = selected.filter(i => i !== item.value) : selected.push(item.value)"
                                         class="block px-4 py-2 text-gray-700 hover:bg-primary-200 hover:text-primary-500 cursor-pointer bg-white w-full"
                                         :class="{ 'bg-primary-200': selected.includes(item.value) }">
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox"
                                                   :checked="selected.includes(item.value)"
                                                   class="w-4 h-4 border-gray-300 rounded focus:ring-primary-500 flex-shrink-0"
                                                   @click.stop>
                                            <span x-text="item.label" class="truncate"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Apply button -->
                <button type="submit"
                        class="w-full md:w-auto inline-flex justify-center font-medium appearance-none border border-primary-700 bg-primary-700 rounded px-8 py-2 text-base text-white hover:bg-primary-800 ring-0 peer">
                    Apply Filters
                </button>
            </div>

            <!-- Selected filters summary -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-2">
                    <template x-for="dropdown in dropdowns" :key="dropdown.name">
                        <template x-if="$refs[dropdown.name] !== undefined">
                            <template x-for="item in getSelectedItems(dropdown)" :key="item.value">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-base bg-primary-100 text-primary-800">
                                    <span x-text="item.label"></span>
                                    <button type="button"
                                            @click="$refs[dropdown.name].selected = $refs[dropdown.name].selected.filter(i => i !== item.value); $refs[dropdown.name].$el.dispatchEvent(new Event('input'))"
                                            class="ml-2 inline-flex items-center p-0.5 hover:bg-primary-200 rounded-full">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>
                        </template>
                    </template>
                </div>
            </div>
        </form>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @for($i = 0; $i < 10; $i++)
                
                <!-- Product Cards with Enhanced Design -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden transform transition-all hover:-translate-y-1 hover:shadow-2xl">
                    <!-- Foto Profil Fotografer -->
                    <a href="{{route('show')}}">
                    <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722163/AbhirajK/Abhirajk%20mykare.webp" alt="Alex Johnson" class="w-full h-48 object-cover">
                  
                    <div class="p-6">
                      <!-- Nama dan Role -->
                      <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Alex Johnson</h2>
                      <p class="text-gray-600 dark:text-gray-400 text-sm">Wedding & Prewedding Specialist</p>
                  
                      <!-- Info Paket -->
                      <div class="mt-4">
                        <h3 class="text-md font-medium text-gray-700 dark:text-gray-200">Winter Special Package</h3>
                        <div class="flex justify-between items-center mt-2">
                          <p class="text-primary-600 font-bold">Start From Rp.1.000.000</p>
                          {{-- <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Limited</span> --}}
                        </div>
                      </div>
                  
                      <!-- Tombol Aksi -->
                      {{-- <button class="mt-4 w-full bg-primary-500 text-white py-2 px-4 rounded-lg hover:bg-primary-600 transition-colors">
                        Book Now
                      </button>
                   --}}
                      
                    </div>
                    </a> 
                  </div>
                @endfor
            </div>
    </div>
</body>
</html>