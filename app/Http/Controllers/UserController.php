<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }


    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:boshliq,xodim',
            'auth_code' => 'required|string'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('users.index');
        }
        else
            abort(403, 'Sizga bu sahifaga kirish taqiqlangan.');
    }


    public function update(Request $request, User $user)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'auth_code' => 'required|string'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }
        if ($request->filled('role')) {
            $validated['role'] = $request->role;
        }

        $user->update($validated);

        return redirect()->route('users.index');
        }
        else
            abort(403, 'Sizga bu sahifaga kirish taqiqlangan.');

    }

    public function destroy(User $user)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            $user->delete();
            return back();
        }
        else
            abort(403, 'Sizga bu sahifaga kirish taqiqlangan.');
    }
}

