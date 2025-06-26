@extends('layouts.admin')

@section('title', 'City Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('admin.cities.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all mr-4">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">City Details</h1>
                <p class="text-white/80">View city information</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.cities.edit', $city) }}" 
               class="bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-300 px-4 py-2 rounded-lg transition-all">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Are you sure you want to delete this city?')"
                        class="bg-red-500/20 hover:bg-red-500/30 text-red-300 px-4 py-2 rounded-lg transition-all">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <!-- City Information -->
    <div class="content-glass rounded-lg border border-white/20 p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-xl font-semibold text-white mb-4">Basic Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">ID</label>
                        <p class="text-white text-lg">{{ $city->id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">City Name</label>
                        <p class="text-white text-lg font-semibold">{{ $city->name }}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-xl font-semibold text-white mb-4">Timestamps</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">Created At</label>
                        <p class="text-white text-lg">{{ $city->created_at->format('M d, Y H:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">Updated At</label>
                        <p class="text-white text-lg">{{ $city->updated_at->format('M d, Y H:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection