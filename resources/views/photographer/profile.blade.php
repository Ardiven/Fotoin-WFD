@extends('layout.dashboard')

@section('title', 'Profile Data')

@section('styles')
<style>
    .form-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .form-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    .form-input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.4);
        outline: none;
    }
    .profile-image-preview {
        width: 120px;
        height: 120px;
        object-fit: cover;
    }
    .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.05);
    }
    
    /* Sticky action buttons styles */
    .sticky-actions {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 40;
        backdrop-filter: blur(20px);
        background: rgba(0, 0, 0, 0.8);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
    }
    
    /* Add padding to prevent content from being hidden behind sticky buttons */
    .form-container {
        padding-bottom: 100px; /* Adjust based on sticky button height */
    }
    
    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* Custom scrollbar for better appearance */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>
@endsection

@section('content')
<div x-data="profileApp()" class="p-6 px-32">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-white text-3xl font-bold mb-2">Profile Data</h1>
        <p class="text-white/70">Manage your professional information and contact details</p>
    </div>

    <!-- Profile Form Container -->
    <div class="form-container">
        {{-- Photographer Profile Form --}}
        <div x-data="photographerProfile()" class="max-w-4xl mx-auto">
            <form id="photographer-profile-form" action="{{ route('photographer.profile.update') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                @csrf
                
                {{-- Display validation errors --}}
                @if ($errors->any())
                    <div class="glass-effect border border-red-500/20 rounded-lg p-4 mb-6 bg-red-500/10">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-2"></i>
                            <h3 class="text-red-400 font-medium">Terdapat kesalahan pada form:</h3>
                        </div>
                        <ul class="text-red-300 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Success message --}}
                @if (session('success'))
                    <div class="glass-effect border border-green-500/20 rounded-lg p-4 mb-6 bg-green-500/10">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            <p class="text-green-300">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Basic Information Section -->
                <div class="glass-effect border border-white/20 rounded-lg p-6 mb-6">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-400"></i>
                        </div>
                        <h2 class="text-white text-xl font-semibold">Informasi Dasar</h2>
                    </div>

                    <!-- Profile Photo -->
                    <div class="mb-6">
                        <label class="block text-white text-sm font-medium mb-3">Foto Profil</label>
                        <div class="flex items-center space-x-6">
                            <div class="relative">
                                <img x-show="profilePhotoPreview" 
                                     :src="profilePhotoPreview" 
                                     class="w-24 h-24 object-cover rounded-lg border border-white/20 bg-white/10"
                                     alt="Profile Preview">
                                <div x-show="!profilePhotoPreview" 
                                     class="w-24 h-24 bg-white/10 border border-white/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-white/40 text-2xl"></i>
                                </div>
                                <div class="absolute inset-0 bg-black/40 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer"
                                     @click="$refs.photoInput.click()">
                                    <i class="fas fa-camera text-white text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <input type="file" 
                                       name="profile_photo"
                                       x-ref="photoInput"
                                       @change="handlePhotoUpload($event)"
                                       accept="image/jpeg,image/jpg,image/png" 
                                       class="hidden">
                                <button type="button" 
                                        @click="$refs.photoInput.click()"
                                        class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-white text-sm transition-colors">
                                    <i class="fas fa-upload mr-2"></i>Upload Foto
                                </button>
                                <p class="text-white/60 text-xs mt-2">Maksimal 2MB, format JPG/PNG</p>
                                @error('profile_photo')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="full_name" class="block text-white text-sm font-medium mb-2">Nama Lengkap *</label>
                            <input type="text" 
                                   id="name"
                                   name="name"
                                   value="{{ old('full_name', Auth::user()->name ?? '') }}"
                                   class="w-full text-white px-4 py-3 form-input rounded-lg bg-white/10 border border-white/30 @error('full_name') border-red-500/50 @enderror"
                                   placeholder="{{ old('full_name', $photographer->name ?? '') }}"
                                   required>
                            @error('full_name')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-white text-sm font-medium mb-2">Email *</label>
                            <input type="email" 
                                   id="email"
                                   name="email"
                                   value="{{ old('email', Auth::user()->email ?? '') }}"
                                   class="w-full px-4 py-3 text-white form-input rounded-lg bg-white/10 border border-white/30 @error('email') border-red-500/50 @enderror"
                                   placeholder="{{ old('email', Auth::user()->email ?? '') }}"
                                   required>
                            @error('email')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-white text-sm font-medium mb-2">Nomor Telepon *</label>
                            <input type="tel" 
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', Auth::user()->photographerProfile->phone ?? '') }}"
                                   class="w-full px-4 py-3 form-input text-white rounded-lg bg-white/10 border border-white/30 @error('phone') border-red-500/50 @enderror"
                                   placeholder="08123456789"
                                   required>
                            @error('phone')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp (optional) -->
                        <div>
                            <label for="whatsapp" class="block text-white text-sm font-medium mb-2">WhatsApp</label>
                            <input type="tel" 
                                   id="whatsapp"
                                   name="whatsapp"
                                   value="{{ old('whatsapp', Auth::user()->photographerProfile->whatsapp ?? '') }}"
                                   class="w-full px-4 py-3 form-input text-white rounded-lg bg-white/10 border border-white/30 @error('whatsapp') border-red-500/50 @enderror"
                                   placeholder="08123456789 (jika berbeda)">
                            @error('whatsapp')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bio/Description -->
                    <div class="mt-6">
                        <label for="bio" class="block text-white text-sm font-medium mb-2">Bio/Deskripsi Singkat *</label>
                        <textarea id="bio"
                                  name="bio"
                                  class="w-full px-4 py-3 form-input text-white rounded-lg h-24 bg-white/10 border border-white/30 resize-none @error('bio') border-red-500/50 @enderror"
                                  placeholder="{{ old('bio', Auth::user()->photographerProfile->bio ?? '') }}"
                                  maxlength="200"
                                  x-model="bioText"
                                  required>{{ e(old('bio', Auth::user()->photographerProfile->bio ?? '')) }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <div>
                                @error('bio')
                                    <p class="text-red-400 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-white/60 text-xs">
                                <span x-text="bioText.length"></span>/200 karakter
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="glass-effect border border-white/20 rounded-lg p-6 mb-6">
                    <!-- Header -->
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-briefcase text-purple-400"></i>
                        </div>
                        <h2 class="text-white text-xl font-semibold">Informasi Profesional</h2>
                    </div>

                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                        <!-- Genre Spesialisasi -->
                        <div x-data="{ 
                            open: false, 
                            search: '',
                            selectedSpecialties: @js(Auth::user() && Auth::user()->specialties
    ? Auth::user()->specialties->pluck('id')->toArray()
    : [])

                        }">
                            <div class="mb-2">
                                <label class="block text-white text-sm font-medium mb-2">Spesialisasi *</label>
                                
                                <!-- Display selected specialties -->
                                <div class="mb-3 min-h-[40px] p-2 bg-white/5 border border-white/20 rounded-lg">
                                    @if(Auth::user() && Auth::user()->specialties->count() > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(Auth::user()->specialties as $specialty)
                                                <span class="inline-flex items-center px-2 py-1 bg-purple-500/20 text-purple-300 text-xs rounded-full">
                                                    {{ $specialty->name }}
                                                    <button type="button" onclick="uncheckSpecialty({{ $specialty->id }})" class="ml-1 hover:text-purple-100">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-white/60 text-sm">Belum ada spesialisasi dipilih</span>
                                    @endif
                                </div>

                                <button type="button"
                                    @click="open = true"
                                    class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Pilih Spesialisasi
                                </button>
                            </div>

                            <!-- Modal Genre -->
                            <div x-show="open" class="fixed inset-0 bg-black/50 backdrop-blur flex justify-center items-center z-50">
                                <div @click.away="open = false" class="bg-white rounded-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto mx-4">
                                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Pilih Spesialisasi</h2>

                                    <input type="text" x-model="search" placeholder="Cari Spesialisasi..."
                                        class="w-full mb-4 px-3 py-2 border rounded-lg">

                                    <div class="grid grid-cols-1 gap-3 max-h-60 overflow-y-auto">
                                        @foreach($specialties as $specialty)
                                            <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded cursor-pointer"
                                                x-show="!search || '{{ strtolower($specialty->name) }}'.includes(search.toLowerCase())">
                                                <input type="checkbox" 
                                                       name="specialties[]" 
                                                       value="{{ $specialty->id }}"
                                                       {{ Auth::user() && Auth::user()->specialties->contains('id', $specialty->id) ? 'checked' : '' }}
                                                       class="text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                                <span class="text-gray-700">{{ $specialty->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button"
                                            @click="open = false"
                                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors">
                                            Selesai
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            @error('specialties')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kota Layanan -->
                        <div x-data="{ 
                            open: false, 
                            search: '',
                            selectedCities: @js(Auth::user() && Auth::user()->cities
                                            ? Auth::user()->cities->pluck('id')->toArray()
                                            : [])

                        }">
                            <div class="mb-2">
                                <label class="block text-white text-sm font-medium mb-2">Kota Layanan *</label>
                                
                                <!-- Display selected cities -->
                                <div class="mb-3 min-h-[40px] p-2 bg-white/5 border border-white/20 rounded-lg">
                                    @if(Auth::user() && Auth::user()->cities->count() > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(Auth::user()->cities as $city)
                                                <span class="inline-flex items-center px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded-full">
                                                    {{ $city->name }}
                                                    <button type="button" onclick="uncheckCity({{ $city->id }})" class="ml-1 hover:text-blue-100">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-white/60 text-sm">Belum ada kota dipilih</span>
                                    @endif
                                </div>

                                <button type="button"
                                    @click="open = true"
                                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-map-marker-alt mr-2"></i>Pilih Kota
                                </button>
                            </div>

                            <!-- Modal Kota -->
                            <div x-show="open" class="fixed inset-0 bg-black/50 backdrop-blur flex justify-center items-center z-50">
                                <div @click.away="open = false" class="bg-white rounded-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto mx-4">
                                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Pilih Kota Layanan</h2>

                                    <input type="text" x-model="search" placeholder="Cari kota..."
                                        class="w-full mb-4 px-3 py-2 border rounded-lg">

                                    <div class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto">
                                        @foreach($cities as $city)
                                            <label class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer"
                                                x-show="!search || '{{ strtolower($city->name) }}'.includes(search.toLowerCase())">
                                                <input type="checkbox" 
                                                       name="cities[]" 
                                                       value="{{ $city->id }}"
                                                       {{ Auth::user() && Auth::user()->cities->contains('id', $city->id) ? 'checked' : '' }}
                                                       class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="text-gray-700 text-sm">{{ $city->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button"
                                            @click="open = false"
                                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors">
                                            Selesai
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            @error('cities')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pengalaman -->
                        <div>
                            <label for="experience_years" class="block text-white text-sm font-medium mb-2">Pengalaman *</label>
                            <div class="relative">
                                <select id="experience_years"
                                        name="experience_years" 
                                        required
                                        class="bg-white/10 border border-white/30 w-full px-4 py-3 form-input rounded-lg appearance-none cursor-pointer @error('experience_years') border-red-500/50 @enderror">
                                    <option value="">Pilih Pengalaman</option>
                                    <option value="0-1" {{ old('experience_years', Auth::user()->photographerProfile->experience_years ?? '') == '0-1' ? 'selected' : '' }}>Kurang dari 1 tahun</option>
                                    <option value="1-2" {{ old('experience_years', Auth::user()->photographerProfile->experience_years ?? '') == '1-2' ? 'selected' : '' }}>1-2 tahun</option>
                                    <option value="3-5" {{ old('experience_years', Auth::user()->photographerProfile->experience_years ?? '') == '3-5' ? 'selected' : '' }}>3-5 tahun</option>
                                    <option value="6-10" {{ old('experience_years', Auth::user()->photographerProfile->experience_years ?? '') == '6-10' ? 'selected' : '' }}>6-10 tahun</option>
                                    <option value="10+" {{ old('experience_years', Auth::user()->photographerProfile->experience_years ?? '') == '10+' ? 'selected' : '' }}>Lebih dari 10 tahun</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-white/60 pointer-events-none"></i>
                            </div>
                            @error('experience_years')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Scroll to top button for long forms -->
                <div class="text-center mb-6">
                    <button type="button" 
                            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-white text-sm transition-colors">
                        <i class="fas fa-arrow-up mr-2"></i>Kembali ke Atas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sticky Action Buttons -->
    <div class="sticky-actions">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <button type="button" 
                        onclick="if(confirm('Apakah Anda yakin ingin mereset form?')) { document.querySelector('#photographer-profile-form').reset(); window.location.reload(); }"
                        class="px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-white transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset
                </button>
                
                <div class="flex space-x-4">
                    <button type="button" 
                            @click="saveAsDraft()"
                            class="px-6 py-3 bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-500/40 rounded-lg text-yellow-300 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Draft
                    </button>
                    
                    <button type="submit" 
                            form="photographer-profile-form"
                            class="px-6 py-3 bg-green-500/20 hover:bg-green-500/30 border border-green-500/40 rounded-lg text-green-300 transition-colors">
                        <i class="fas fa-check mr-2"></i>Simpan Profil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function photographerProfile() {
        return {
            profilePhotoPreview: '{{ isset(Auth::user()->profile_photo) ? asset("storage/" . Auth::user()->profile_photo) : "" }}',
            bioText: '{{ old("bio", Auth::user()->bio ?? "") }}',

            handlePhotoUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    // Validate file size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file maksimal 2MB');
                        event.target.value = '';
                        return;
                    }

                    // Validate file type
                    if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                        alert('Format file harus JPG atau PNG');
                        event.target.value = '';
                        return;
                    }

                    // Create preview
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.profilePhotoPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            saveAsDraft() {
                // Set draft flag and submit
                const form = document.querySelector('#photographer-profile-form');
                const draftInput = document.createElement('input');
                draftInput.type = 'hidden';
                draftInput.name = 'is_draft';
                draftInput.value = '1';
                form.appendChild(draftInput);
                form.submit();
            },

            submitForm(event) {
                // You can add additional validation here if needed
                event.target.submit();
            }
        }
    }

    // Global functions to handle uncheck operations
    function uncheckSpecialty(specialtyId) {
        const checkbox = document.querySelector(`input[name="specialties[]"][value="${specialtyId}"]`);
        if (checkbox) {
            checkbox.checked = false;
            // Trigger form update or page refresh to update display
            location.reload();
        }
    }

    function uncheckCity(cityId) {
        const checkbox = document.querySelector(`input[name="cities[]"][value="${cityId}"]`);
        if (checkbox) {
            checkbox.checked = false;
            // Trigger form update or page refresh to update display
            location.reload();
        }
    }
    </script>

    <!-- Success/Error Messages -->
    <div x-show="showMessage" 
         x-transition
         :class="messageType === 'success' ? 'bg-green-500/20 border-green-500/40 text-green-300' : 'bg-red-500/20 border-red-500/40 text-red-300'"
         class="fixed top-20 right-6 z-50 px-6 py-4 border rounded-lg glass-effect">
        <div class="flex items-center">
            <i :class="messageType === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'" class="mr-3"></i>
            <span x-text="message"></span>
            <button @click="showMessage = false" class="ml-4 hover:opacity-70">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function profileApp() {
        return {
            showMessage: false,
            message: '',
            messageType: 'success',
            
            profileData: {
                profilePhoto: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120"%3E%3Crect width="120" height="120" fill="%23667eea"/%3E%3Ctext x="60" y="70" text-anchor="middle" fill="white" font-family="Arial" font-size="24"%3EJS%3C/text%3E%3C/svg%3E',
                fullName: 'John Smith',
                email: 'john.smith@email.com',
                phone: '08123456789',
                whatsapp: '',
                bio: 'Passionate wedding photographer with expertise in capturing life\'s most precious moments.',
                specialization: 'wedding',
                experience: '3-5',
                serviceLocation: 'Jakarta, Bogor, Depok, Tangerang',
                websiteUrl: 'https://johnsmithphoto.com',
                certifications: ''
            },
            
            init() {
                console.log('Profile app initialized');
            },
            
            handlePhotoUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        this.showNotification('File terlalu besar. Maksimal 2MB.', 'error');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.profileData.profilePhoto = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            
            isFormValid() {
                return this.profileData.fullName && 
                       this.profileData.email && 
                       this.profileData.phone && 
                       this.profileData.bio && 
                       this.profileData.specialization && 
                       this.profileData.experience && 
                       this.profileData.serviceLocation;
            },
            
            saveProfile() {
                if (!this.isFormValid()) {
                    this.showNotification('Mohon lengkapi semua field yang wajib diisi.', 'error');
                    return;
                }
                
                // TODO: Implement Laravel AJAX call
                console.log('Saving profile:', this.profileData);
                this.showNotification('Profil berhasil disimpan!', 'success');
            },
            
            saveAsDraft() {
                // TODO: Implement Laravel AJAX call
                console.log('Saving as draft:', this.profileData);
                this.showNotification('Draft berhasil disimpan!', 'success');
            },
            
            resetForm() {
                if (confirm('Apakah Anda yakin ingin mereset form? Semua perubahan akan hilang.')) {
                    this.profileData = {
                        profilePhoto: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120"%3E%3Crect width="120" height="120" fill="%23667eea"/%3E%3Ctext x="60" y="70" text-anchor="middle" fill="white" font-family="Arial" font-size="24"%3EJS%3C/text%3E%3C/svg%3E',
                        fullName: '',
                        email: '',
                        phone: '',
                        whatsapp: '',
                        bio: '',
                        specialization: '',
                        experience: '',
                        serviceLocation: '',
                        websiteUrl: '',
                        certifications: ''
                    };
                    this.showNotification('Form berhasil direset.', 'success');
                }
            },
            
            showNotification(msg, type = 'success') {
                this.message = msg;
                this.messageType = type;
                this.showMessage = true;
                setTimeout(() => {
                    this.showMessage = false;
                }, 5000);
            }
        }
    }
</script>
@endsection