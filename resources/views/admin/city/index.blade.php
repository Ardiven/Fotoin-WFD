@extends('layout.admin')

@section('title', 'Cities Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Cities Management</h1>
            <p class="text-white/80">Manage all cities in the system</p>
        </div>
        <a href="{{ route('admin.cities.create') }}" 
           class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg transition-all flex items-center">
            <i class="fas fa-plus mr-2"></i>Add New City
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="glass-effect border border-green-400/30 bg-green-400/10 text-green-300 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Cities Table -->
    <div class="content-glass rounded-lg border border-white/20 overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/20">
                            <th class="text-left py-4 px-2 text-white font-semibold">ID</th>
                            <th class="text-left py-4 px-2 text-white font-semibold">Name</th>
                            <th class="text-left py-4 px-2 text-white font-semibold">Created At</th>
                            <th class="text-center py-4 px-2 text-white font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                        <tr class="border-b border-white/10 hover:bg-white/5 transition-colors">
                            <td class="py-4 px-2 text-white/80">{{ $city->id }}</td>
                            <td class="py-4 px-2 text-white">{{ $city->name }}</td>
                            <td class="py-4 px-2 text-white/80">{{ $city->created_at->format('M d, Y') }}</td>
                            <td class="py-4 px-2">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.cities.show', $city) }}" 
                                       class="bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 px-3 py-2 rounded-lg transition-all">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.cities.edit', $city) }}" 
                                       class="bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-300 px-3 py-2 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this city?')"
                                                class="bg-red-500/20 hover:bg-red-500/30 text-red-300 px-3 py-2 rounded-lg transition-all">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 px-2 text-center text-white/60">
                                <i class="fas fa-city text-4xl mb-4 block"></i>
                                No cities found. <a href="{{ route('admin.cities.create') }}" class="text-blue-300 hover:text-blue-200">Create one now</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($cities->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="bg-white/10 rounded-lg p-2">
                    {{ $cities->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection