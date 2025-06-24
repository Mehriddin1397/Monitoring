<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCodeController extends Controller
{
    public function show()
    {
        return view('pages.auth_code');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'auth_code' => 'required|digits:4',
        ]);

        $user = Auth::user();

        if ($user->auth_code === $request->auth_code) {
            session(['authenticated' => true]);
            session(['last_activity' => now()]);

            // Remember me 7 kun
            Auth::login($user, true);
            return redirect()->route('tasks.index');
        }

        return back()->withErrors(['auth_code' => 'Noto‘g‘ri kod']);
    }
}
