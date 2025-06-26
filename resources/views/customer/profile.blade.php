@extends('layout.app')

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
        <p class="text-white/70">Manage your personal information and photography preferences</p>
    </div>

    <!-- Profile Form Container -->
    <div class="form-container">
        {{-- Customer Profile Form --}}
        <div x-data="customerProfile()" class="max-w-4xl mx-auto">
            <form id="customer-profile-form" action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
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
                            <label for="name" class="block text-white text-sm font-medium mb-2">Nama Lengkap *</label>
                            <input type="text" 
                                   id="name"
                                   name="name"
                                   value="{{ old('name', Auth::user()->name ?? '') }}"
                                   class="w-full text-white px-4 py-3 form-input rounded-lg bg-white/10 border border-white/30 @error('name') border-red-500/50 @enderror"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            @error('name')
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
                                   placeholder="nama@email.com"
                                   required>
                            @error('email')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-white text-sm font-medium mb-2">Nomor Telepon</label>
                            <input type="tel" 
                                   id="phone_number"
                                   name="phone_number"
                                   value="{{ old('phone_number', Auth::user()->customerProfile->phone_number ?? '') }}"
                                   class="w-full px-4 py-3 form-input text-white rounded-lg bg-white/10 border border-white/30 @error('phone_number') border-red-500/50 @enderror"
                                   placeholder="08123456789">
                            @error('phone_number')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-white text-sm font-medium mb-2">Kota</label>
                            <input type="text" 
                                   id="city"
                                   name="city"
                                   value="{{ old('city', Auth::user()->customerProfile->city ?? '') }}"
                                   class="w-full px-4 py-3 form-input text-white rounded-lg bg-white/10 border border-white/30 @error('city') border-red-500/50 @enderror"
                                   placeholder="Nama kota tempat tinggal">
                            @error('city')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <label for="address" class="block text-white text-sm font-medium mb-2">Alamat Lengkap</label>
                        <textarea id="address"
                                  name="address"
                                  class="w-full px-4 py-3 form-input text-white rounded-lg h-20 bg-white/10 border border-white/30 resize-none @error('address') border-red-500/50 @enderror"
                                  placeholder="Masukkan alamat lengkap">{{ old('address', Auth::user()->customerProfile->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Photography Preferences Section -->
                <div class="glass-effect border border-white/20 rounded-lg p-6 mb-6">
                    <!-- Header -->
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-camera text-purple-400"></i>
                        </div>
                        <h2 class="text-white text-xl font-semibold">Preferensi Fotografi</h2>
                    </div>

                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                        <!-- Preferred Photography Type -->
                        <div>
                            <label for="preferred_photography_type" class="block text-white text-sm font-medium mb-2">Jenis Fotografi yang Diminati</label>
                            <div class="relative">
                                <select id="preferred_photography_type"
                                        name="preferred_photography_type" 
                                        class="bg-white/10 border border-white/30 w-full px-4 py-3 form-input rounded-lg appearance-none cursor-pointer @error('preferred_photography_type') border-red-500/50 @enderror">
                                    <option value="">Pilih Jenis Fotografi</option>
                                    @foreach ($specialties as $specialty)
                                    <option value="{{ $specialty->name }}" {{ old('preferred_photography_type', Auth::user()->customerProfile->preferred_photography_type ?? '') == $specialty->name ? 'selected' : '' }}>{{ $specialty->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-white/60 pointer-events-none"></i>
                            </div>
                            @error('preferred_photography_type')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Budget Range -->
                        <div>
                            <label for="budget_range" class="block text-white text-sm font-medium mb-2">Budget Range</label>
                            <div class="relative">
                                <select id="budget_range"
                                        name="budget_range" 
                                        class="bg-white/10 border border-white/30 w-full px-4 py-3 form-input rounded-lg appearance-none cursor-pointer @error('budget_range') border-red-500/50 @enderror">
                                    <option value="">Pilih Range Budget</option>
                                    <option value="under_1m" {{ old('budget_range', Auth::user()->customerProfile->budget_range ?? '') == 'under_1m' ? 'selected' : '' }}>Di bawah Rp 1.000.000</option>
                                    <option value="1m_3m" {{ old('budget_range', Auth::user()->customerProfile->budget_range ?? '') == '1m_3m' ? 'selected' : '' }}>Rp 1.000.000 - Rp 3.000.000</option>
                                    <option value="3m_5m" {{ old('budget_range', Auth::user()->customerProfile->budget_range ?? '') == '3m_5m' ? 'selected' : '' }}>Rp 3.000.000 - Rp 5.000.000</option>
                                    <option value="5m_10m" {{ old('budget_range', Auth::user()->customerProfile->budget_range ?? '') == '5m_10m' ? 'selected' : '' }}>Rp 5.000.000 - Rp 10.000.000</option>
                                    <option value="10m_20m" {{ old('budget_range', Auth::user()->customerProfile->budget_range ?? '') == '10m_20m' ? 'selected' : '' }}>Rp 10.000.000 - Rp 20.000.000</option>
                                    <option value="above_20m" {{ old('budget_range', Auth::user()->customerProfile->budget_range ?? '') == 'above_20m' ? 'selected' : '' }}>Di atas Rp 20.000.000</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-white/60 pointer-events-none"></i>
                            </div>
                            @error('budget_range')
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
                        onclick="if(confirm('Apakah Anda yakin ingin mereset form?')) { document.querySelector('#customer-profile-form').reset(); window.location.reload(); }"
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
                            form="customer-profile-form"
                            class="px-6 py-3 bg-green-500/20 hover:bg-green-500/30 border border-green-500/40 rounded-lg text-green-300 transition-colors">
                        <i class="fas fa-check mr-2"></i>Simpan Profil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function customerProfile() {
        return {
            profilePhotoPreview: '{{ isset(Auth::user()->profile_photo) ? asset("storage/" . Auth::user()->profile_photo) : "" }}',
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
            submitForm(event) {
                // You can add additional validation here if needed
                event.target.submit();
            },

            saveAsDraft() {
                // Set draft flag and submit
                const form = document.querySelector('#customer-profile-form');
                const draftInput = document.createElement('input');
                draftInput.type = 'hidden';
                draftInput.name = 'is_draft';
                draftInput.value = '1';
                form.appendChild(draftInput);
                form.submit();
            }
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
            
            init() {
                console.log('Customer profile app initialized');
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