<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
{
    $user = Auth::user();

    if ($user->hasRole('customer')) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_photo'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'preferred_photography_type' => 'nullable|string|max:100',
            'budget_range' => 'nullable|in:under_1m,1m_3m,3m_5m,5m_10m,10m_20m,above_20m',
        ]);

        if ($request->hasFile('profile_photo')) {
            $profile_photo = $request->file('profile_photo');
            $profile_photo_path = $profile_photo->store('profile_photos', 'public');
            $user->profile_photo = $profile_photo_path;
            $user->save();
        }

        // Update user basic info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update atau create customer profile
        $profileData = [
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'preferred_photography_type' => $request->preferred_photography_type,
            'budget_range' => $request->budget_range,
        ];

        // Gunakan updateOrCreate untuk menangani kasus jika profile belum ada
        $user->customerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // Handle draft save
        if ($request->has('is_draft')) {
            return back()->with('success', 'Draft profil customer berhasil disimpan.');
        }

        return back()->with('success', 'Profil customer berhasil diperbarui.');
    }
    elseif($user->hasRole('photographer')){
        // Photographer
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'profile_photo'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'required',
            'whatsapp' => 'required',
            'bio' => 'required',
            'experience_years' => 'required',
            'specialties' => 'required|array|min:1',
            'cities' => 'required|array|min:1',
        ]);

        if ($request->hasFile('profile_photo')) {
            $profile_photo = $request->file('profile_photo');
            $profile_photo_path = $profile_photo->store('profile_photos', 'public');
            $user->profile_photo = $profile_photo_path;
            $user->save();
        }

        $photographer = $user->PhotographerProfile;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        

        if (!$photographer) {
            $photographer = $user->PhotographerProfile()->create([
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'bio' => $request->bio,
                'experience_years' => $request->experience_years,
            ]);
        } else {
            $photographer = $user->PhotographerProfile;
            $photographer->phone = $request->phone;
            $photographer->whatsapp = $request->whatsapp;
            $photographer->bio = $request->bio;
            $photographer->experience_years = $request->experience_years;
        }

        $photographer->save();
        $user->specialties()->sync($request->specialties);
        $user->cities()->sync($request->cities);

        return back()->with('success', 'Profil fotografer berhasil diperbarui.');
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function showProfile(){
        if (Auth::user()->hasRole('customer')) {
            $cities = City::all();
            $specialties = Specialty::all();
            return view('customer.profile', compact('cities', 'specialties'));
        } elseif (Auth::user()->hasRole('photographer')) {
            $specialties = Specialty::all();
            $cities = City::all();
            return view('photographer.Profile', compact('specialties', 'cities'));
        }
    }
}
