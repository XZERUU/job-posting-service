<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SeekerProfile;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SeekerApiController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->seekerProfile;

        $profileData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $profile->phone ?? '',
            'location' => $profile->location ?? '',
            'headline' => $profile->headline ?? '',
            'about' => $profile->about ?? '',
            'education' => is_array($profile->education) ? $profile->education : [],
            'experiences' => is_array($profile->experiences) ? $profile->experiences : [],
            'linkedin_url' => $profile->linkedin_url ?? '',
            'portfolio_url' => $profile->portfolio_url ?? '',
            'github_url' => $profile->github_url ?? '',
            'resume_path' => $profile->resume_path ? Storage::url($profile->resume_path) : null,
            'profile_completed' => !empty($profile->phone) && !empty($profile->location),
        ];

        $skills = [];
        if ($profile && is_array($profile->skills)) {
            $skills = $profile->skills;
        }

        return response()->json([
            'profile' => $profileData,
            'skills' => $skills
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->seekerProfile ?? new SeekerProfile(['user_id' => $user->id]);

        if ($request->has('name')) {
            $user->name = $request->input('name');
            $user->save();
        }

        $profile->phone = $request->input('phone', $profile->phone);
        $profile->location = $request->input('location', $profile->location);
        $profile->headline = $request->input('headline', $profile->headline);
        $profile->about = $request->input('about', $profile->about);
        
        // Mobile sends these as JSON strings if using FormData, or arrays if using JSON.
        $education = $request->input('education', $profile->education);
        if (is_string($education)) {
            $education = json_decode($education, true);
        }
        $profile->education = is_array($education) ? $education : [];

        $experiences = $request->input('experiences', $profile->experiences);
        if (is_string($experiences)) {
            $experiences = json_decode($experiences, true);
        }
        $profile->experiences = is_array($experiences) ? $experiences : [];

        $profile->linkedin_url = $request->input('linkedin_url', $profile->linkedin_url);
        $profile->portfolio_url = $request->input('portfolio_url', $profile->portfolio_url);
        $profile->github_url = $request->input('github_url', $profile->github_url);
        
        $skillsInput = $request->input('skills', []);
        if (is_string($skillsInput)) {
            $skillsInput = json_decode($skillsInput, true);
        }
        $profile->skills = is_array($skillsInput) ? $skillsInput : [];

        if ($request->hasFile('resume')) {
            $request->validate([
                'resume' => 'file|mimes:pdf|max:2048',
            ]);
            
            if ($profile->resume_path) {
                Storage::disk('public')->delete($profile->resume_path);
            }
            
            $path = $request->file('resume')->store('resumes', 'public');
            $profile->resume_path = $path;
        }

        $profile->save();

        return response()->json([
            'message' => 'Profile updated successfully',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        
        if ($user->seekerProfile && $user->seekerProfile->resume_path) {
            Storage::disk('public')->delete($user->seekerProfile->resume_path);
        }
        
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }

    public function getSkills()
    {
        $skills = Skill::select('id', 'skill_name', 'category')->get();
        return response()->json([
            'skills' => $skills
        ]);
    }
}

