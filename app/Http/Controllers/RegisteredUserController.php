<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'string'], // Ensure role is validated
    ]);

    $user = User::create([
        'name' => $request->first_name . ' ' . $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role, // Saving the role
    ]);

    event(new Registered($user));

    Auth::login($user);

    // --- Redirection Logic ---
    if ($user->role === 'employer') {
        return redirect('/employer/dashboard'); // Change this to your employer route
    }

    return redirect(RouteServiceProvider::HOME); // Stays at Seeker Dashboard
}
}
