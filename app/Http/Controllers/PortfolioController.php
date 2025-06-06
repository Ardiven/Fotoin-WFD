<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Specialty;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the portfolio items.
     */
    public function index(Request $request)
    {
        $query = Portfolio::with('specialty')->where('user_id', Auth::id());

        // Filter by specialty
        if ($request->filled('specialty_id')) {
            $query->where('specialty_id', $request->specialty_id);
        }

        // Sort portfolio items
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'featured':
                $query->orderByDesc('is_featured')->orderByDesc('created_at');
                break;
            case 'specialty':
                $query->join('specialties', 'portfolios.specialty_id', '=', 'specialties.id')
                      ->orderBy('specialties.name', 'asc')
                      ->select('portfolios.*');
                break;
            default: // newest
                $query->orderByDesc('created_at');
                break;
        }

        $portfolioItems = $query->get();
        $totalItems = $query->count();
        
        // Get all specialties for filter dropdown
        $specialties = Auth::user()->specialties;

        return view('portfolio.index', compact('portfolioItems', 'specialties', 'totalItems'));
    }

    /**
     * Show the form for creating a new portfolio item.
     */
    public function create()
    {
        $specialties = \App\Models\Specialty::orderBy('name')->get();
        return view('portfolio.create', compact('specialties'));
    }

    /**
     * Store a newly created portfolio item in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'description' => 'nullable|string|max:300',
            'image' => 'required|image|mimes:jpeg,jpg,png', // 5MB max
            'is_featured' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check the form errors below.');
        }

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('portfolio', $imageName, 'public');
            }

            // Create portfolio item
            Portfolio::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'specialty_id' => $request->specialty_id,
                'description' => $request->description,
                'image_path' => $imagePath,
                'is_featured' => $request->boolean('is_featured'),
                'is_public' => $request->boolean('is_public', true),
                'views' => 0,
                'likes' => 0,
            ]);

            return redirect()->route('photographer.portfolio.index')
                ->with('success', 'Portfolio item added successfully!');

        } catch (\Exception $e) {
            // Delete uploaded image if portfolio creation fails
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create portfolio item. Please try again.');
        }
    }

    /**
     * Display the specified portfolio item.
     */
    public function show(Portfolio $portfolio)
    {
        // Check if user owns this portfolio item or if it's public
        if ($portfolio->user_id !== Auth::id() && !$portfolio->is_public) {
            abort(403, 'This portfolio item is private.');
        }

        // Increment view count if not the owner
        if ($portfolio->user_id !== Auth::id()) {
            $portfolio->increment('views');
        }

        return view('portfolio.show', compact('portfolio'));
    }

    /**
     * Show the form for editing the specified portfolio item.
     */
    public function edit(Portfolio $portfolio)
    {
        // Check if user owns this portfolio item
        if ($portfolio->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own portfolio items.');
        }

        // Return JSON response for AJAX request
        if (request()->wantsJson()) {
            return response()->json([
                'id' => $portfolio->id,
                'title' => $portfolio->title,
                'specialty_id' => $portfolio->specialty_id,
                'description' => $portfolio->description,
                'is_featured' => $portfolio->is_featured,
                'is_public' => $portfolio->is_public,
                'image_url' => Storage::url($portfolio->image_path),
            ]);
        }

        $specialties = \App\Models\Specialty::orderBy('name')->get();
        return view('portfolio.edit', compact('portfolio', 'specialties'));
    }

    /**
     * Update the specified portfolio item in storage.
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        // Check if user owns this portfolio item
        if ($portfolio->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own portfolio items.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'description' => 'nullable|string|max:300',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
            'is_featured' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check the form errors below.');
        }

        try {
            $oldImagePath = $portfolio->image_path;

            // Handle image upload if new image is provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('portfolio', $imageName, 'public');
                
                // Delete old image
                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            } else {
                $imagePath = $oldImagePath;
            }

            // Update portfolio item
            $portfolio->update([
                'title' => $request->title,
                'specialty_id' => $request->specialty_id,
                'description' => $request->description,
                'image_path' => $imagePath,
                'is_featured' => $request->boolean('is_featured'),
                'is_public' => $request->boolean('is_public'),
            ]);

            return redirect()->route('portfolio.index')
                ->with('success', 'Portfolio item updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update portfolio item. Please try again.');
        }
    }

    /**
     * Remove the specified portfolio item from storage.
     */
    public function destroy(Portfolio $portfolio)
    
    {
        if (!$portfolio) {
    return redirect()->back()->with('error', 'Portfolio not found.');
}


        try {
            // Delete image file
            if ($portfolio->image_path && Storage::disk('public')->exists($portfolio->image_path)) {
                Storage::disk('public')->delete($portfolio->image_path);
            }

            // Delete portfolio record
            $portfolio->delete();

            return redirect()->route('photographer.portfolio.index')
                ->with('success', 'Portfolio item deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Delete Portfolio Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete portfolio item. Please try again.');
        }
    }

    /**
     * Toggle featured status of portfolio item.
     */
    public function toggleFeatured(Portfolio $portfolio)
    {
        // Check if user owns this portfolio item
        if ($portfolio->user_id !== Auth::id()) {
            abort(403, 'You can only modify your own portfolio items.');
        }

        $portfolio->update([
            'is_featured' => !$portfolio->is_featured
        ]);

        return response()->json([
            'success' => true,
            'is_featured' => $portfolio->is_featured,
            'message' => $portfolio->is_featured ? 'Item featured successfully!' : 'Item unfeatured successfully!'
        ]);
    }

    /**
     * Toggle public status of portfolio item.
     */
    public function togglePublic(Portfolio $portfolio)
    {
        // Check if user owns this portfolio item
        if ($portfolio->user_id !== Auth::id()) {
            abort(403, 'You can only modify your own portfolio items.');
        }

        $portfolio->update([
            'is_public' => !$portfolio->is_public
        ]);

        return response()->json([
            'success' => true,
            'is_public' => $portfolio->is_public,
            'message' => $portfolio->is_public ? 'Item made public successfully!' : 'Item made private successfully!'
        ]);
    }

    /**
     * Like a portfolio item.
     */
    public function like(Portfolio $portfolio)
    {
        // Check if portfolio is public or user owns it
        if (!$portfolio->is_public && $portfolio->user_id !== Auth::id()) {
            abort(403, 'This portfolio item is private.');
        }

        // Don't allow users to like their own portfolio
        if ($portfolio->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot like your own portfolio item.'
            ], 403);
        }

        $portfolio->increment('likes');

        return response()->json([
            'success' => true,
            'likes' => $portfolio->likes,
            'message' => 'Portfolio item liked!'
        ]);
    }

    /**
     * Get portfolio statistics for dashboard.
     */
    public function getStats()
    {
        $userId = Auth::id();
        
        $stats = [
            'total_items' => Portfolio::where('user_id', $userId)->count(),
            'total_views' => Portfolio::where('user_id', $userId)->sum('views'),
            'total_likes' => Portfolio::where('user_id', $userId)->sum('likes'),
            'featured_items' => Portfolio::where('user_id', $userId)->where('is_featured', true)->count(),
            'public_items' => Portfolio::where('user_id', $userId)->where('is_public', true)->count(),
            'private_items' => Portfolio::where('user_id', $userId)->where('is_public', false)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk delete portfolio items.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portfolio_ids' => 'required|array|min:1',
            'portfolio_ids.*' => 'exists:portfolios,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid portfolio items selected.');
        }

        try {
            $portfolios = Portfolio::whereIn('id', $request->portfolio_ids)
                ->where('user_id', Auth::id())
                ->get();

            foreach ($portfolios as $portfolio) {
                // Delete image file
                if ($portfolio->image_path && Storage::disk('public')->exists($portfolio->image_path)) {
                    Storage::disk('public')->delete($portfolio->image_path);
                }

                // Delete portfolio record
                $portfolio->delete();
            }

            return redirect()->route('portfolio.index')
                ->with('success', count($portfolios) . ' portfolio items deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete portfolio items. Please try again.');
        }
    }
}