<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Shows your custom Job Seeker Dashboard
    public function show(): View
    {
        $user = Auth::user();
        $profile = $user->profile ?? (object)[]; 

        return view('seeker-profile', [
            'user' => $user,
            'profile' => $profile,
            'recentApplications' => [], 
            'recommendedJobs' => [],
            'stats' => ['applied' => 0, 'under_review' => 0, 'saved' => 0]
        ]);
    }

    // Shows the Account Settings (Breeze default)
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->save();
        return Redirect::to('/profile')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', ['password' => ['required', 'current_password']]);
        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
    
    public function updateCustom(Request $request) {
    $validated = $request->validate([
        'phone' => 'nullable|string|max:20',
        'headline' => 'nullable|string|max:255',
        'resume' => 'nullable|file|mimes:pdf|max:2048',
    ]);

    // Logic to save the data to your 'profiles' table
    // Logic to store the file (if it exists) using $request->file('resume')->store('resumes');

    return back()->with('status', 'profile-updated');
}
}
