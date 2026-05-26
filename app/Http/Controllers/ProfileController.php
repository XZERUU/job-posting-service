<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Shows your custom Job Seeker Dashboard
    public function show(): View
    {
        $user = Auth::user();
        $profile = $user->seekerProfile ?? (object)[];
        $applications = $user->applications()
            ->with('jobPost.employer')
            ->latest()
            ->get();

        return view('seeker-profile', [
            'user' => $user,
            'profile' => $profile,
            'recentApplications' => $applications->take(5),
            'recommendedJobs' => [],
            'stats' => [
                'applied' => $applications->count(),
                'under_review' => $applications->where('status', 'pending')->count(),
                'approved' => $applications->where('status', 'approved')->count(),
                'profile_views' => 0,
            ],
        ]);
    }

    // Shows the Account Settings (Breeze default)
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'profile' => $request->user()->seekerProfile ?? (object) [],
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
    
    public function updateCustom(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'headline' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'about' => 'nullable|string|max:1000',
            'skills' => 'nullable|string|max:1000',
            'education_degree' => 'nullable|string|max:255',
            'education_school' => 'nullable|string|max:255',
            'education_year_from' => 'nullable|string|max:20',
            'education_year_to' => 'nullable|string|max:20',
            'experience_position' => 'nullable|string|max:255',
            'experience_company' => 'nullable|string|max:255',
            'experience_year_from' => 'nullable|string|max:20',
            'experience_year_to' => 'nullable|string|max:20',
            'resume' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $profile = $request->user()->seekerProfile()->firstOrNew([
            'user_id' => $request->user()->id,
        ]);

        $profile->phone = $validated['phone'] ?? null;
        $profile->headline = $validated['headline'] ?? null;
        $profile->location = $validated['location'] ?? null;
        $profile->about = $validated['about'] ?? null;
        $profile->skills = $this->parseCommaList($validated['skills'] ?? null);
        $profile->education = $this->profileListEntry($validated, 'education', [
            'degree',
            'school',
            'year_from',
            'year_to',
        ]);
        $profile->experiences = $this->profileListEntry($validated, 'experience', [
            'position',
            'company',
            'year_from',
            'year_to',
        ]);

        if ($request->hasFile('resume')) {
            if ($profile->resume_path) {
                Storage::disk('public')->delete($profile->resume_path);
            }

            $profile->resume_path = $request->file('resume')->store('resumes', 'public');
        }

        $profile->save();

        return back()->with('status', 'profile-updated');
    }

    private function parseCommaList(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function profileListEntry(array $validated, string $prefix, array $fields): array
    {
        $entry = [];

        foreach ($fields as $field) {
            $entry[$field] = $validated["{$prefix}_{$field}"] ?? null;
        }

        $hasValue = collect($entry)->filter(fn ($value) => filled($value))->isNotEmpty();

        return $hasValue ? [$entry] : [];
    }

    public function updateLinks(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
        ]);

        $profile = $request->user()->seekerProfile()->firstOrNew([
            'user_id' => $request->user()->id,
        ]);

        $profile->fill($validated);
        $profile->save();

        return back()->with('status', 'links-updated');
    }
}
