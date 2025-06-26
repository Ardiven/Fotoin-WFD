<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
         $photographerId = auth()->id();

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $query = Booking::whereHas('package', function ($q) use ($photographerId) {
            $q;
        });

        // Total Booking (confirmed)
        $totalBookings = (clone $query)->whereIn('status', ['confirmed', 'completed'])->count();

        // Total Booking Bulan Ini
        $totalBookingsThisMonth = (clone $query)
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Total Revenue (confirmed)
        $totalRevenue = (clone $query)
            ->where('status', 'completed')
            ->sum('total_price');

        // Total Revenue Bulan Ini (confirmed)
        $totalRevenueThisMonth = (clone $query)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_price');


        return view('admin.index', compact(
            'totalBookings',
            'totalBookingsThisMonth',
            'totalRevenue',
            'totalRevenueThisMonth'
        ));
    }
    public function indexUser(Request $request){
        $query = User::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        // if ($request->has('role') && $request->role) {
        //     $query->where('role', $request->role);
        // }
        
        // Filter by status
        // if ($request->has('status') && $request->status !== '') {
        //     $query->where('is_active', $request->status);
        // }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.user.index', compact('users'));
    }
    public function showCreateUser()
    {
        return view('admin.users.create');
    }
    
    /**
     * Store a newly created user
     */
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user,photographer',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'email_verified_at' => now()
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }
    
    /**
     * Display the specified user
     */
    public function showuser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
    
    /**
     * Show the form for editing the specified user
     */
    public function showEditUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update the specified user
     */
    public function updateUser(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user,photographer',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];
        
        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        
        $user->update($updateData);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }
    
    /**
     * Remove the specified user
     */
    public function destroyUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }
    
    /**
     * Toggle user status
     */
    public function toggleStatusUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()
            ->with('success', "User berhasil {$status}!");
    }





    public function indexCity(){
         $cities = City::latest()->paginate(10);
        
        return view('admin.city.index', compact('cities'));
    }
    public function showCreateCity()
    {
        return view('admin.cities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cities,name',
        ]);

        City::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function showCity(City $city)
    {
        return view('admin.cities.show', compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editCity(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCity(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cities,name,' . $city->id,
        ]);

        $city->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCity(City $city)
    {
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully.');
    }



    public function indexSpecialty(){
         $specialties = DB::table('specialties')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.specialty.index', compact('specialties'));
    }
     public function showCreateSpecialty()
    {
        return view('admin.specialties.create');
    }
    public function storeSpecialty(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        // Set default values
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        DB::table('specialties')->insert(array_merge($validated, [
            'created_at' => now(),
            'updated_at' => now()
        ]));

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Specialty created successfully.');
    
    }

    public function showSpecialty(string $id)
    {
        $specialty = DB::table('specialties')->where('id', $id)->first();

        if (!$specialty) {
            abort(404);
        }

        return view('admin.specialties.show', compact('specialty'));
    }

    public function showEditSpecialty(string $id)
    {
        $specialty = DB::table('specialties')->where('id', $id)->first();

        if (!$specialty) {
            abort(404);
        }

        return view('admin.specialties.edit', compact('specialty'));
    }

    public function updateSpecialty(Request $request, string $id)
    {
        $specialty = DB::table('specialties')->where('id', $id)->first();

        if (!$specialty) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('specialties')->ignore($id)],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        // Set default values
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        DB::table('specialties')
            ->where('id', $id)
            ->update(array_merge($validated, [
                'updated_at' => now()
            ]));

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Specialty updated successfully.');
    }
    public function destroySpecialty(string $id)
    {
        $specialty = DB::table('specialties')->where('id', $id)->first();

        if (!$specialty) {
            abort(404);
        }

        DB::table('specialties')->where('id', $id)->delete();

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Specialty deleted successfully.');
    }

    public function toggleStatusSpecialty(string $id)
    {
        $specialty = DB::table('specialties')->where('id', $id)->first();

        if (!$specialty) {
            abort(404);
        }

        DB::table('specialties')
            ->where('id', $id)
            ->update([
                'is_active' => !$specialty->is_active,
                'updated_at' => now()
            ]);

        $status = $specialty->is_active ? 'deactivated' : 'activated';
        
        return redirect()->route('admin.specialties.index')
            ->with('success', "Specialty {$status} successfully.");
    }

}
