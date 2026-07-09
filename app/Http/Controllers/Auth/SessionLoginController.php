<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionLoginController extends Controller
{
    public function create()
    {
        return view ('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'   => ['required', 'email'],
            'password'=> ['required', 'string'],
        ]); 

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Those credentials doesnot match our records.',
            ]);
        }
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}