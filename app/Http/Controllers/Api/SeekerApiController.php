<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SeekerProfile;
use App\Models\Skill;
use Illuminate\Http\Request;

class SeekerApiController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->seekerProfile;

        // Split name into first and last name for mobile compatibility
        $nameParts = explode(' ', $user->name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        $locationParts = explode(',', (string) ($profile->location ?? ''));
        $city = trim($locationParts[0] ?? '');
        $province = trim($locationParts[1] ?? '');

        // Extract from JSON fields if they exist
        $education = is_array($profile->education) ? $profile->education : [];
        $experiences = is_array($profile->experiences) ? $profile->experiences : [];

        $profileData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $user->email,
            'contact_number' => $profile->phone ?? '',
            'city' => $city,
            'province' => $province,
            'education_level' => $education['level'] ?? '',
            'course' => $education['course'] ?? '',
            'years_of_experience' => $experiences['years'] ?? 0,
            'employment_status' => $profile->about ?? '', // Mapping this loosely
            'preferred_occupation' => $profile->headline ?? '',
            // Fake these since they aren't in the DB schema
            'profile_completed' => !empty($profile->phone) && !empty($profile->location),
            'referral_status' => 'referral_ready',
        ];

        // Format skills to an array of objects for mobile
        $skills = [];
        if ($profile && is_array($profile->skills)) {
            foreach ($profile->skills as $i => $skillName) {
                $skills[] = [
                    'id' => $i + 1,
                    'skill_name' => $skillName
                ];
            }
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

        $firstName = $request->input('first_name', '');
        $lastName = $request->input('last_name', '');
        
        $user->name = trim("$firstName $lastName");
        $user->save();

        $profile->phone = $request->input('contact_number', $profile->phone);
        
        $city = $request->input('city', '');
        $province = $request->input('province', '');
        if ($city || $province) {
            $profile->location = trim("$city, $province", ", ");
        }

        $profile->headline = $request->input('preferred_occupation', $profile->headline);
        $profile->about = $request->input('employment_status', $profile->about);
        
        $profile->education = [
            'level' => $request->input('education_level', ''),
            'course' => $request->input('course', ''),
        ];

        $profile->experiences = [
            'years' => $request->input('years_of_experience', 0),
        ];

        $profile->save();

        return response()->json([
            'message' => 'Profile updated successfully',
        ]);
    }

    public function updateSkills(Request $request)
    {
        $user = $request->user();
        $profile = $user->seekerProfile ?? new SeekerProfile(['user_id' => $user->id]);

        // Mobile sends an array of skill objects, we just need the names
        $skillNames = [];
        $skillsInput = $request->input('skills', []);
        
        foreach ($skillsInput as $sk) {
            if (isset($sk['skill_name'])) {
                $skillNames[] = $sk['skill_name'];
            } elseif (is_string($sk)) {
                $skillNames[] = $sk;
            }
        }

        $profile->skills = $skillNames;
        $profile->save();

        return response()->json([
            'message' => 'Skills updated successfully',
        ]);
    }

    public function getSkills()
    {
        $skills = Skill::select('id', 'skill_name', 'category')->get();
        
        return response()->json([
            'skills' => $skills
        ]);
    }
}
