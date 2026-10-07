<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Simulasi registrasi — belum ada database user
        return redirect()->route('auth.register.success')->with('registered_name', $validated['name']);
    }

    public function registerSuccess()
    {
        return view('pages.auth.register-success');
    }

    public function showLogin()
    {
        return view('pages.auth.login');
    }
}
