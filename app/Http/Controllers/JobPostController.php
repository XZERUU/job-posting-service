<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobRequiredSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobPostController extends Controller
{
    /**
     * Helper to get user info from headers or request attributes
     * In a microservices architecture, the API Gateway usually populates X-User-Id and X-User-Role.
     */
    private function getUserFromRequest(Request $request)
    {
        $userId = $request->header('X-User-Id');
        $role = $request->header('X-User-Role');

        // Fallback for direct development / testing without gateway
        if (!$userId) {
            $userId = $request->input('user_id');
            $role = $request->input('user_role', 'job_seeker');
        }

        return [
            'id' => $userId,
            'role' => $role
        ];
    }

    /**
     * Display a listing of the active job posts with search/filter.
     * GET /api/jobs
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $jobType = $request->query('job_type');
        $location = $request->query('location');

        try {
            $query = JobPost::select('job_posts.*', 'employers.company_name', 'employers.business_type')
                ->join('employers', 'employers.id', '=', 'job_posts.employer_id')
                ->where('job_posts.status', 'active')
                ->where('employers.approval_status', 'approved');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('job_posts.job_title', 'like', '%' . $search . '%')
                      ->orWhere('job_posts.job_description', 'like', '%' . $search . '%')
                      ->orWhere('employers.company_name', 'like', '%' . $search . '%');
                });
            }

            if ($jobType) {
                $query->where('job_posts.job_type', $jobType);
            }

            if ($location) {
                $query->where('job_posts.location', 'like', '%' . $location . '%');
            }

            $jobs = $query->orderBy('job_posts.posted_at', 'desc')->get();

            return response()->json([
                'jobs' => $jobs
            ]);
        } catch (\Exception $e) {
            Log::error('[Laravel Job Listing Error] ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch jobs'
            ], 500);
        }
    }

    /**
     * Display the specified job post with details, required skills, and user application status.
     * GET /api/jobs/{id}
     */
    public function show(Request $request, string $id)
    {
        try {
            $job = JobPost::select('job_posts.*', 'employers.company_name', 'employers.company_address', 
                                   'employers.contact_person', 'employers.contact_number', 'employers.business_type')
                ->join('employers', 'employers.id', '=', 'job_posts.employer_id')
                ->where('job_posts.id', $id)
                ->first();

            if (!$job) {
                return response()->json([
                    'error' => 'Job not found'
                ], 404);
            }

            // Get required skills
            $skills = DB::table('job_required_skills')
                ->join('skills', 'skills.id', '=', 'job_required_skills.skill_id')
                ->where('job_required_skills.job_post_id', $id)
                ->select('skills.id', 'skills.skill_name', 'skills.category', 
                         'job_required_skills.required_level', 'job_required_skills.is_required')
                ->get();

            $job->required_skills = $skills;

            // Check if current user is a job seeker and has already applied
            $user = $this->getUserFromRequest($request);
            if ($user['id'] && $user['role'] === 'job_seeker') {
                $jobSeeker = DB::table('job_seekers')
                    ->where('user_id', $user['id'])
                    ->first();

                if ($jobSeeker) {
                    $application = DB::table('job_applications')
                        ->where('job_post_id', $id)
                        ->where('job_seeker_id', $jobSeeker->id)
                        ->select('id', 'application_status', 'applied_at')
                        ->first();

                    $job->my_application = $application ?: null;
                }
            }

            return response()->json([
                'job' => $job
            ]);
        } catch (\Exception $e) {
            Log::error('[Laravel Job Show Error] ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch job details'
            ], 500);
        }
    }

    /**
     * Compare a job seeker's skills to the job's required skills (Rule-Based Skill Comparison).
     * GET /api/jobs/{id}/match
     */
    public function matchSkills(Request $request, string $id)
    {
        try {
            $user = $this->getUserFromRequest($request);

            if (!$user['id'] || $user['role'] !== 'job_seeker') {
                return response()->json([
                    'error' => 'Only job seekers can view skill match'
                ], 403);
            }

            // Fetch job seeker profile
            $jobSeeker = DB::table('job_seekers')
                ->where('user_id', $user['id'])
                ->first();

            if (!$jobSeeker) {
                return response()->json([
                    'error' => 'Profile not found'
                ], 404);
            }

            // Fetch required skills for the job
            $requiredSkills = DB::table('job_required_skills')
                ->join('skills', 'skills.id', '=', 'job_required_skills.skill_id')
                ->where('job_required_skills.job_post_id', $id)
                ->select('skills.id', 'skills.skill_name', 'skills.category', 
                         'job_required_skills.required_level', 'job_required_skills.is_required')
                ->get();

            // Fetch seeker's profile skills
            $seekerSkills = DB::table('job_seeker_skills')
                ->join('skills', 'skills.id', '=', 'job_seeker_skills.skill_id')
                ->where('job_seeker_skills.job_seeker_id', $jobSeeker->id)
                ->select('skills.id', 'skills.skill_name', 'skills.category', 
                         'job_seeker_skills.proficiency_level')
                ->get();

            $seekerSkillIds = $seekerSkills->pluck('id')->toArray();

            $matched = $requiredSkills->filter(function ($skill) use ($seekerSkillIds) {
                return in_array($skill->id, $seekerSkillIds);
            })->values();

            $unmatched = $requiredSkills->filter(function ($skill) use ($seekerSkillIds) {
                return !in_array($skill->id, $seekerSkillIds);
            })->values();

            return response()->json([
                'notice' => 'Rule-based skill comparison only. No ranking or recommendation.',
                'total_required' => $requiredSkills->count(),
                'matched_count' => $matched->count(),
                'unmatched_count' => $unmatched->count(),
                'matched_skills' => $matched,
                'unmatched_required_skills' => $unmatched,
            ]);
        } catch (\Exception $e) {
            Log::error('[Laravel Skill Match Error] ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to compare skills'
            ], 500);
        }
    }

    /**
     * Store a newly created job post in storage.
     * POST /api/jobs
     */
    public function store(Request $request)
    {
        $request->validate([
            'employer_id' => 'required|integer',
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_type' => 'nullable|string|in:full-time,part-time,contract,temporary',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'vacancies' => 'nullable|integer',
            'requirements' => 'nullable|string',
            'status' => 'nullable|string|in:active,closed,draft',
            'closing_date' => 'nullable|date',
            'skills' => 'nullable|array', // Array of ['skill_id' => 1, 'required_level' => 'intermediate', 'is_required' => true]
        ]);

        try {
            DB::beginTransaction();

            $jobPost = JobPost::create([
                'employer_id' => $request->employer_id,
                'job_title' => $request->job_title,
                'job_description' => $request->job_description,
                'job_type' => $request->job_type ?? 'full-time',
                'salary_min' => $request->salary_min,
                'salary_max' => $request->salary_max,
                'location' => $request->location,
                'vacancies' => $request->vacancies ?? 1,
                'requirements' => $request->requirements,
                'status' => $request->status ?? 'active',
                'posted_at' => now(),
                'closing_date' => $request->closing_date
            ]);

            // Save skills if provided
            if ($request->has('skills')) {
                foreach ($request->skills as $skill) {
                    JobRequiredSkill::create([
                        'job_post_id' => $jobPost->id,
                        'skill_id' => $skill['skill_id'],
                        'required_level' => $skill['required_level'] ?? 'beginner',
                        'is_required' => $skill['is_required'] ?? true
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Job posting created successfully',
                'job' => $jobPost
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[Laravel Job Store Error] ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create job posting'
            ], 500);
        }
    }

    /**
     * Update the specified job post in storage.
     * PUT/PATCH /api/jobs/{id}
     */
    public function update(Request $request, string $id)
    {
        $jobPost = JobPost::find($id);

        if (!$jobPost) {
            return response()->json([
                'error' => 'Job posting not found'
            ], 404);
        }

        $request->validate([
            'job_title' => 'nullable|string|max:255',
            'job_description' => 'nullable|string',
            'job_type' => 'nullable|string|in:full-time,part-time,contract,temporary',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'vacancies' => 'nullable|integer',
            'requirements' => 'nullable|string',
            'status' => 'nullable|string|in:active,closed,draft',
            'closing_date' => 'nullable|date',
            'skills' => 'nullable|array', // New array of skills to replace existing ones
        ]);

        try {
            DB::beginTransaction();

            $jobPost->update(filter_null_values($request->only([
                'job_title', 'job_description', 'job_type', 'salary_min', 'salary_max', 
                'location', 'vacancies', 'requirements', 'status', 'closing_date'
            ])));

            if ($request->has('skills')) {
                // Delete existing skills
                JobRequiredSkill::where('job_post_id', $id)->delete();

                // Save new skills
                foreach ($request->skills as $skill) {
                    JobRequiredSkill::create([
                        'job_post_id' => $id,
                        'skill_id' => $skill['skill_id'],
                        'required_level' => $skill['required_level'] ?? 'beginner',
                        'is_required' => $skill['is_required'] ?? true
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Job posting updated successfully',
                'job' => $jobPost
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[Laravel Job Update Error] ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update job posting'
            ], 500);
        }
    }

    /**
     * Remove the specified job post from storage.
     * DELETE /api/jobs/{id}
     */
    public function destroy(string $id)
    {
        $jobPost = JobPost::find($id);

        if (!$jobPost) {
            return response()->json([
                'error' => 'Job posting not found'
            ], 404);
        }

        try {
            $jobPost->delete();
            return response()->json([
                'message' => 'Job posting deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('[Laravel Job Destroy Error] ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to delete job posting'
            ], 500);
        }
    }
}

// Helper function to remove null values for partial updates
if (!function_exists('filter_null_values')) {
    function filter_null_values($array) {
        return array_filter($array, function ($value) {
            return $value !== null;
        });
    }
}
