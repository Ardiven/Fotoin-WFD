<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of all specialties
     */
    public function index()
    {
        $specialties = Specialty::withCount('portfolioItems')
                               ->orderBy('name')
                               ->get();
        
        return view('specialties.index', compact('specialties'));
    }
    
    /**
     * Show portfolio items for a specific specialty
     */
    public function show(Request $request, Specialty $specialty)
    {
        $query = $specialty->portfolioItems()->with(['user', 'specialty']);
        
        // Search within specialty
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('technologies', 'like', "%{$search}%");
            });
        }
        
        // Featured only filter
        if ($request->boolean('featured_only')) {
            $query->where('is_featured', true);
        }
        
        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderByRaw('(COALESCE(views, 0) + COALESCE(likes, 0)) DESC')
                      ->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $portfolioItems = $query->paginate(12)->withQueryString();
        
        // Get all specialties for navigation
        $allSpecialties = Specialty::withCount('portfolioItems')
                                  ->orderBy('name')
                                  ->get();
        
        return view('specialties.show', compact(
            'specialty', 
            'portfolioItems', 
            'allSpecialties'
        ));
    }
    
    /**
     * Show the form for creating a new specialty (Admin only)
     */
    public function create()
    {
        return view('admin.specialties.create');
    }
    
    /**
     * Store a newly created specialty (Admin only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:specialties',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:50'
        ]);
        
        $specialty = Specialty::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
            'icon' => $request->icon ?? 'fas fa-tag'
        ]);
        
        return redirect()->route('admin.specialties.index')
                        ->with('success', 'Specialty created successfully!');
    }
    
    /**
     * Display the specified specialty in admin panel
     */
    public function edit(Specialty $specialty)
    {
        return view('admin.specialties.edit', compact('specialty'));
    }
    
    /**
     * Update the specified specialty (Admin only)
     */
    public function update(Request $request, Specialty $specialty)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name,' . $specialty->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:50'
        ]);
        
        $specialty->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color ?? $specialty->color,
            'icon' => $request->icon ?? $specialty->icon
        ]);
        
        return redirect()->route('admin.specialties.index')
                        ->with('success', 'Specialty updated successfully!');
    }
    
    /**
     * Remove the specified specialty (Admin only)
     */
    public function destroy(Specialty $specialty)
    {
        // Check if specialty has portfolio items
        if ($specialty->portfolioItems()->count() > 0) {
            return redirect()->route('admin.specialties.index')
                            ->with('error', 'Cannot delete specialty that has portfolio items. Please reassign or remove the portfolio items first.');
        }
        
        $specialty->delete();
        
        return redirect()->route('admin.specialties.index')
                        ->with('success', 'Specialty deleted successfully!');
    }
    
    /**
     * Get specialty statistics
     */
    public function getStats(Specialty $specialty)
    {
        $stats = [
            'total_items' => $specialty->portfolioItems()->count(),
            'featured_items' => $specialty->portfolioItems()->where('is_featured', true)->count(),
            'total_views' => $specialty->portfolioItems()->sum('views') ?? 0,
            'total_likes' => $specialty->portfolioItems()->sum('likes') ?? 0,
            'recent_items' => $specialty->portfolioItems()
                                      ->where('created_at', '>=', now()->subDays(30))
                                      ->count(),
            'top_contributors' => $specialty->portfolioItems()
                                           ->with('user')
                                           ->get()
                                           ->groupBy('user_id')
                                           ->map(function($items) {
                                               return [
                                                   'user' => $items->first()->user,
                                                   'count' => $items->count()
                                               ];
                                           })
                                           ->sortByDesc('count')
                                           ->take(5)
                                           ->values()
        ];
        
        return response()->json($stats);
    }
}