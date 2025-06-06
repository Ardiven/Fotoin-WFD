<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

  public function showLogin()
    {
        return view('auth.login');
    }
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->hasRole('photographer')) {
                return redirect()->route('photographer.overview');
            } elseif ($user->hasRole('customer')) {
                return redirect()->route('customer.index');
            } else {
                Auth::logout();
                return redirect()->route('user.login')->withErrors(['role' => 'Role tidak dikenali']);
            }
        }


        return back()->withErrors(['email' => 'Email atau password salah']);
    }
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'user_type' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);        

        $user->assignRole($request->user_type);

        return redirect()->route('user.login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
    public function dashboard()
    {
        return view('costumer.index');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login');
    }

}
