<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showPinForm()
    {
        return view('auth.pin');
    }

    public function setup(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        User::create([
            'pin_hash' => Hash::make($request->pin),
        ]);

        session(['authenticated' => true]);

        return redirect('dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        $user = User::first();

        if (!$user || !Hash::check($request->pin, $user->pin_hash)) {
            return back()->withErrors(['pin' => 'PIN salah']);
        }

        session(['authenticated' => true]);

        return redirect('dashboard');
    }

    public function logout()
    {
        session()->forget('authenticated');
        return redirect('/pin');
    }
}
