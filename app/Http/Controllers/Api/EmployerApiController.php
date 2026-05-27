<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployerApiController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'profile' => [
                'company_name' => $user->name,
                'approval_status' => 'approved', // Fake approval since there's no employer approval in DB
            ]
        ]);
    }

    public function getJobs(Request $request)
    {
        $jobs = JobPost::withCount('applications as applicant_count')
            ->where('employer_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'jobs' => $jobs
        ]);
    }

    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'location' => 'required|string|max:255',
            'vacancies' => 'required|integer|min:1',
            'requirements' => 'nullable|string',
            'closing_date' => 'nullable|date',
        ]);

        $validated['employer_id'] = $request->user()->id;
        $validated['status'] = 'active'; // Default active for simplicity, instead of pending
        $validated['posted_at'] = now();

        DB::beginTransaction();
        try {
            $job = JobPost::create($validated);

            if ($request->has('required_skills')) {
                foreach ($request->required_skills as $skillId) {
                    DB::table('job_required_skills')->insert([
                        'job_post_id' => $job->id,
                        'skill_id' => $skillId,
                        'is_required' => true,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Job posted successfully.', 'job' => $job], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to post job'], 500);
        }
    }

    public function updateJob(Request $request, $id)
    {
        $job = JobPost::where('employer_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'location' => 'required|string|max:255',
            'vacancies' => 'required|integer|min:1',
            'requirements' => 'nullable|string',
            'closing_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $job->update($validated);

            if ($request->has('required_skills')) {
                DB::table('job_required_skills')->where('job_post_id', $job->id)->delete();
                foreach ($request->required_skills as $skillId) {
                    DB::table('job_required_skills')->insert([
                        'job_post_id' => $job->id,
                        'skill_id' => $skillId,
                        'is_required' => true,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Job updated successfully.', 'job' => $job]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update job'], 500);
        }
    }

    public function closeJob(Request $request, $id)
    {
        $job = JobPost::where('employer_id', $request->user()->id)->findOrFail($id);
        
        $job->update(['status' => 'closed']);

        return response()->json(['message' => 'Job closed successfully.']);
    }

    public function getApplicants(Request $request, $id)
    {
        $job = JobPost::where('employer_id', $request->user()->id)->findOrFail($id);

        $applications = Application::with('user.seekerProfile')
            ->where('job_post_id', $job->id)
            ->latest()
            ->get();

        $formatted = $applications->map(function ($app) {
            $user = $app->user;
            $profile = $user->seekerProfile;

            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $locationParts = explode(',', (string) ($profile->location ?? ''));
            $city = trim($locationParts[0] ?? '');
            $province = trim($locationParts[1] ?? '');

            $education = is_array($profile->education) ? $profile->education : [];
            $experiences = is_array($profile->experiences) ? $profile->experiences : [];

            return [
                'application_id' => $app->id,
                'application_status' => $app->status,
                'cover_letter' => $app->cover_letter,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $user->email,
                'contact_number' => $profile->phone ?? '',
                'city' => $city,
                'province' => $province,
                'education_level' => $education['level'] ?? '',
                'course' => $education['course'] ?? '',
                'years_of_experience' => $experiences['years'] ?? 0,
                'employment_status' => $profile->about ?? '',
                'preferred_occupation' => $profile->headline ?? '',
            ];
        });

        return response()->json([
            'applicants' => $formatted
        ]);
    }

    public function updateApplicationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $application = Application::whereHas('jobPost', function ($query) use ($request) {
            $query->where('employer_id', $request->user()->id);
        })->findOrFail($id);

        $application->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
