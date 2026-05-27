<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;

class JobApiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->search);
        $jobType = trim((string) $request->job_type);

        $jobs = JobPost::with('employer')
            ->where('status', 'active')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('job_title', 'like', "%{$search}%")
                        ->orWhere('job_description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('job_type', 'like', "%{$search}%")
                        ->orWhere('requirements', 'like', "%{$search}%")
                        ->orWhereHas('employer', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($jobType !== '', function ($query) use ($jobType) {
                $query->where('job_type', $jobType);
            })
            ->latest()
            ->get();

        // Format for mobile app
        $formattedJobs = $jobs->map(function ($job) {
            $data = $job->toArray();
            $data['company_name'] = $job->employer ? $job->employer->name : 'Unknown';
            return $data;
        });

        return response()->json([
            'jobs' => $formattedJobs
        ]);
    }

    public function show(Request $request, $id)
    {
        $job = JobPost::with(['employer', 'applications' => function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        }])->findOrFail($id);

        $data = $job->toArray();
        $data['company_name'] = $job->employer ? $job->employer->name : 'Unknown';
        
        // Map my_application if the user has applied
        $data['my_application'] = $job->applications->first() ? $job->applications->first()->toArray() : null;

        return response()->json([
            'job' => $data
        ]);
    }

    public function match(Request $request, $id)
    {
        $job = JobPost::findOrFail($id);
        $user = $request->user();
        $profile = $user->seekerProfile;

        // Ensure user has a profile
        $seekerSkills = [];
        if ($profile && is_array($profile->skills)) {
            $seekerSkills = array_map('strtolower', array_map('trim', $profile->skills));
        }

        // Get job required skills
        $requiredSkills = \DB::table('job_required_skills')
            ->join('skills', 'job_required_skills.skill_id', '=', 'skills.id')
            ->where('job_required_skills.job_post_id', $job->id)
            ->where('job_required_skills.is_required', true)
            ->select('skills.skill_name', 'skills.id')
            ->get();

        $matched = [];
        $unmatched = [];

        foreach ($requiredSkills as $reqSkill) {
            $reqName = strtolower(trim($reqSkill->skill_name));
            $isMatched = false;

            foreach ($seekerSkills as $userSkill) {
                if (str_contains($reqName, $userSkill) || str_contains($userSkill, $reqName)) {
                    $isMatched = true;
                    break;
                }
            }

            if ($isMatched) {
                $matched[] = [
                    'id' => $reqSkill->id,
                    'skill_name' => $reqSkill->skill_name,
                ];
            } else {
                $unmatched[] = [
                    'id' => $reqSkill->id,
                    'skill_name' => $reqSkill->skill_name,
                ];
            }
        }

        return response()->json([
            'matched_count' => count($matched),
            'unmatched_count' => count($unmatched),
            'total_required' => count($requiredSkills),
            'matched_skills' => $matched,
            'unmatched_required_skills' => $unmatched,
            'notice' => 'This is a rule-based check based on encoded skills. It is not an automated hiring decision.',
        ]);
    }
}
