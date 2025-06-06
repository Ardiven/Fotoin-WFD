<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\PhotographerProfile;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PackageRequest;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'created':
                $query->orderBy('created_at', $sortOrder);
                break;
            default:
                $query->orderBy('name', $sortOrder);
        }

        $packages = $query->paginate(12);

        // Calculate stats
        $stats = [
            'total' => Package::count(),
            'active' => Package::where('status', 'active')->count(),
            'popular' => Package::where('is_popular', true)->count(),
            'average_price' => Package::avg('price') ?? 0,
        ];

        return view('packages.index', compact('packages', 'stats'));
    }

    public function create()
    {
        return view('packages.create');
    }

public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required',
            'custom_duration' => 'nullable|numeric|min:1|max:24',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Handle custom duration
        if ($validated['duration'] === 'custom' && isset($validated['custom_duration'])) {
            $validated['duration'] = $validated['custom_duration'];
        }
        unset($validated['custom_duration']);

        // Set default status if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = $validated['is_active'] ? 'active' : 'inactive';
        }
  $package = Package::create($validated);

$features = collect($request->input('features', []))
    ->filter() // buang fitur kosong/null
    ->map(fn($f) => ['name' => $f])
    ->toArray();

$package->features()->createMany($features);


        return redirect()->route('photographer.packages.index')
            ->with('success', 'Package created successfully.');
    }



    public function edit(Package $package)
    {
        return view('packages.create', compact('package'));
    }

    public function update(PackageRequest $request, Package $package)
    {
        $data = $request->validated();
        
        // Handle features array
        if ($request->has('features')) {
            $data['features'] = array_filter($request->features);
        }

        $package->update($data);

        return redirect()->route('packages.index')
                        ->with('success', 'Package updated successfully!');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('photographer.packages.index')
                        ->with('success', 'Package deleted successfully!');
    }
}