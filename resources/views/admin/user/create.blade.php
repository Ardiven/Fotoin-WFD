@extends('layout.admin')

@section('title', 'FotoIn Dashboard - Add New User')

@section('page-header')
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.users.index') }}" class="text-white/60 hover:text-white mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-white">Add New User</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="content-glass border border-white/20 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-6">User Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-white/60 text-sm font-medium mb-2">
                            Full Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:outline-none focus:border-blue-400 @error('name') border-red-400 @enderror"
                               placeholder="Enter full name">
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-white/60 text-sm font-medium mb-2">
                            Email Address <span class="text-red-400">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:outline-none focus:border-blue-400 @error('email') border-red-400 @enderror"
                               placeholder="Enter email address">
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-white/60 text-sm font-medium mb-2">
                            Phone Number
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                               class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:outline-none focus:border-blue-400 @error('phone') border-red-400 @enderror"
                               placeholder="Enter phone number">
                        @error('phone')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-white/60 text-sm font-medium mb-2">
                            User Role <span class="text-red-400">*</span>
                        </label>
                        <select id="role" name="role" required
                                class="w-full bg-white/50 border rounded-lg px-3 py-2 text-dark focus:outline-none focus:border-blue-400 @error('role') border-red-400 @enderror">
                            <option value="">Select Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="photographer" {{ old('role') == 'photographer' ? 'selected' : '' }}>Photographer</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                        @error('role')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="content-glass border border-white/20 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-6">Account Security</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-white/60 text-sm font-medium mb-2">
                            Password <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 pr-10 text-white placeholder-white/50 focus:outline-none focus:border-blue-400 @error('password') border-red-400 @enderror"
                                   placeholder="Enter password">
                            <button type="button" onclick="togglePassword('password')" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white/60 hover:text-white">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-white/60 text-sm font-medium mb-2">
                            Confirm Password <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 pr-10 text-white placeholder-white/50 focus:outline-none focus:border-blue-400"
                                   placeholder="Confirm password">
                            <button type="button" onclick="togglePassword('password_confirmation')" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white/60 hover:text-white">
                                <i class="fas fa-eye" id="password_confirmation-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-glass border border-white/20 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-6">Account Settings</h3>
                
                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-white/10 border-white/20 rounded focus:ring-blue-500 focus:ring-2">
                    <label for="is_active" class="ml-2 text-white">
                        Active User
                        <span class="text-white/60 text-sm block">User can login and access the system</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="glass-effect border border-white/20 text-white px-6 py-2 rounded-lg hover:bg-white/10">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Create User
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<style>
    .glass-effect {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .content-glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
</style>
@endpush

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const eye = document.getElementById(fieldId + '-eye');
        
        if (field.type === 'password') {
            field.type = 'text';
            eye.classList.remove('fa-eye');
            eye.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            eye.classList.remove('fa-eye-slash');
            eye.classList.add('fa-eye');
        }
    }
</script>
@endpush