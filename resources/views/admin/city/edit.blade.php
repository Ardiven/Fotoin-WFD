@extends('layouts.admin')

@section('title', 'Edit City')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.cities.index') }}" 
           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all mr-4">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Edit City</h1>
            <p class="text-white/80">Update city information</p>
        </div>
    </div>

    <!-- Form -->
    <div class="content-glass rounded-lg border border-white/20 p-8">
        <form action="{{ route('admin.cities.update', $city) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="name" class="block text-white font-semibold mb-2">
                    City Name <span class="text-red-400">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $city->name) }}"
                       class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all"
                       placeholder="Enter city name"
                       required>
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit" 
                        class="bg-gradient-primary hover:bg-gradient-secondary text-white px-6 py-3 rounded-lg transition-all flex items-center">
                    <i class="fas fa-save mr-2"></i>Update City
                </button>
                <a href="{{ route('admin.cities.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg transition-all flex items-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection