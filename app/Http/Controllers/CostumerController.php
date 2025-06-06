<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Package;
use App\Models\User;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class CostumerController extends Controller
{
    public function index(){
        $photographers = User::Role('photographer')->get();
        return view('customer.index', compact('photographers'));
    }
    public function showProfile(User $photographer){
        return view('customer.show', compact('photographer'));
    }
    public function showPortfolio(User $photographer){
        return view('customer.showcase', compact('photographer'));
    }
    public function showPhotographers(Request $request)
{
   // Get filter data for dropdowns
        $specialties = Specialty::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        $cities = City::orderBy('name')->get();
        
        // Start building the query
        $query = User::role('photographer')
            ->with(['specialties', 'cities', 'packages' => function($q) {
                $q->where('status', 'active')->orderBy('price', 'asc');
            }])
            ->withCount(['packages' => function($q) {
                $q->where('status', 'active');
            }]);
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->get('search'));
            $query->where(function(Builder $q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%'])
                ->orWhereHas('specialties', function(Builder $sq) use ($searchTerm) {
                    $sq->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
                });
            });
        }
        
        // Apply specialty filter
        if ($request->filled('specialties')) {
            $specialties = $request->get('specialties', []);
            $query->whereHas('specialties', function(Builder $q) use ($specialties) {
                $q->whereIn('name', $specialties);
            });
        }
        
        // Apply location filter
        if ($request->filled('locations')) {
            $locations = $request->get('locations', []);
            $query->whereHas('cities', function(Builder $q) use ($locations) {
                $q->whereIn('name', $locations);
            });
        }
        
        // Apply price range filter
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->get('min_price');
            $maxPrice = $request->get('max_price');
            
            $query->whereHas('packages', function(Builder $q) use ($minPrice, $maxPrice) {
                if ($minPrice) {
                    $q->where('price', '>=', $minPrice);
                }
                if ($maxPrice) {
                    $q->where('price', '<=', $maxPrice);
                }
            });
        }
        
        // Apply rating filter
        // if ($request->filled('rating')) {
        //     $rating = $request->get('rating');
        //     $query->where('rating', '>=', $rating);
        // }
        
        // Apply availability filters
        if ($request->filled('availability')) {
            $availability = $request->get('availability', []);
            
            if (in_array('available', $availability)) {
                $query->where('is_available', true);
            }
            
            if (in_array('weekend', $availability)) {
                $query->where('weekend_available', true);
            }
            
            if (in_array('instant', $availability)) {
                $query->where('instant_booking', true);
            }
        }
        
        // Handle location-based search (if lat/lng provided)
        if ($request->filled('lat') && $request->filled('lng')) {
            $lat = $request->get('lat');
            $lng = $request->get('lng');
            
            // Add distance calculation (assuming you have latitude/longitude in users table)
            $query->selectRaw('*, 
                ( 6371 * acos( cos( radians(?) ) * 
                cos( radians( latitude ) ) * 
                cos( radians( longitude ) - radians(?) ) + 
                sin( radians(?) ) * 
                sin( radians( latitude ) ) ) ) AS distance', 
                [$lat, $lng, $lat])
                ->where('latitude', '!=', null)
                ->where('longitude', '!=', null)
                ->orderBy('distance');
        }
        
        // Apply sorting
        $sort = $request->get('sort', 'recommended');
        switch ($sort) {
            // case 'rating':
            //     $query->orderByDesc('rating');
            //     break;
            case 'price-low':
                $query->whereHas('packages')->with(['packages' => function($q) {
                    $q->orderBy('price', 'asc');
                }]);
                break;
            case 'price-high':
                $query->whereHas('packages')->with(['packages' => function($q) {
                    $q->orderByDesc('price');
                }]);
                break;
            case 'newest':
                $query->orderByDesc('created_at');
                break;
            case 'recommended':
            default:
                $query->orderByDesc('created_at');
                break;
        }
        
        // Get photographers
        $photographers = $query->get();
        
        // If this is an AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'photographers' => $photographers->map(function($photographer) {
                    return [
                        'id' => $photographer->id,
                        'name' => $photographer->name,
                        'profile_photo' => $photographer->profile_photo,
                        'email' => $photographer->email,
                        'rating' => $photographer->rating,
                        'reviews_count' => $photographer->reviews_count ?? 0,
                        'is_available' => $photographer->is_available ?? false,
                        'instant_booking' => $photographer->instant_booking ?? false,
                        'weekend_available' => $photographer->weekend_available ?? false,
                        'specialties' => $photographer->specialties->map(function($specialty) {
                            return [
                                'id' => $specialty->id,
                                'name' => $specialty->name,
                                'slug' => $specialty->slug ?? strtolower(str_replace(' ', '-', $specialty->name))
                            ];
                        }),
                        'cities' => $photographer->cities->map(function($city) {
                            return [
                                'id' => $city->id,
                                'name' => $city->name,
                                'slug' => $city->slug ?? strtolower(str_replace(' ', '-', $city->name))
                            ];
                        }),
                        'packages' => $photographer->packages->map(function($package) {
                            return [
                                'id' => $package->id,
                                'name' => $package->name,
                                'price' => $package->price,
                                'duration' => $package->duration,
                                'description' => $package->description
                            ];
                        }),
                        'packages_count' => $photographer->packages_count ?? 0
                    ];
                }),
                'count' => $photographers->count(),
                'total' => $photographers->count()
            ]);
        }
        
        // Return view for regular requests
        return view('customer.photographer', compact('photographers', 'specialties', 'cities'));
    }

    public function showPayment(Package $package){ {
        $photographer = User::find($package->user_id);
        return view('payment.create', compact('package', 'photographer'));
    }
}
}