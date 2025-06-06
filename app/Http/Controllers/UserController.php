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
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'whatsapp' => 'required',
            'bio' => 'required',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->CustomerProfile->update([
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'bio' => $request->bio,
        ]);

        return back()->with('success', 'Profil customer berhasil diperbarui.');
    }

    // Photographer
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'whatsapp' => 'required',
        'bio' => 'required',
        'experience_years' => 'required',
        'specialties' => 'required|array|min:1',
        'cities' => 'required|array|min:1',
    ]);

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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function showProfile(){
        if (Auth::user()->hasRole('customer')) {
            return view('costumer.index');
        } elseif (Auth::user()->hasRole('photographer')) {
            $specialties = Specialty::all();
            $cities = City::all();
            return view('photographer.Profile', compact('specialties', 'cities'));
        }
    }
}
