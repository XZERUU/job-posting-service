<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Support\SeekerData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Shows your custom Job Seeker Dashboard
    public function show(): View
    {
        $user = Auth::user();
        $profile = SeekerData::profile($user->id);
        $stats = SeekerData::stats($user->id);

        return view('seeker-profile', [
            'user' => $user,
            'profile' => $profile,
            'recentApplications' => SeekerData::applicationRows($user->id),
            'recommendedJobs' => SeekerData::recommendedJobs($user->id),
            'stats' => $stats,
        ]);
    }

    // Shows the Account Settings (Breeze default)
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'profile' => SeekerData::profile($request->user()->id),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
    
    public function updateCustom(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'headline' => 'nullable|string|max:255',
            'resume' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('resume')) {
            $validated['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        unset($validated['resume']);

        DB::table('profiles')->updateOrInsert(
            ['user_id' => $request->user()->id],
            array_merge($validated, [
                'updated_at' => now(),
                'created_at' => now(),
            ])
        );

        return back()->with('status', 'profile-updated');
    }
}
