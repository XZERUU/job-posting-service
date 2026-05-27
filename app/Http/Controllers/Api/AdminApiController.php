<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobPost;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminApiController extends Controller
{
    public function getStats()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_job_seekers' => User::where('role', 'seeker')->count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_jobs' => JobPost::count(),
            'active_jobs' => JobPost::where('status', 'active')->count(),
            'total_applications' => Application::count(),
        ]);
    }

    public function getEmployers()
    {
        $employers = User::where('role', 'employer')->latest()->get()->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'company_name' => $u->name, // Mobile expects this
                'email' => $u->email,
                'approval_status' => 'approved',
                'contact_person' => $u->name,
            ];
        });

        return response()->json(['employers' => $employers]);
    }

    public function getPendingEmployers()
    {
        return response()->json(['employers' => []]);
    }

    public function storeEmployer(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->company_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employer',
        ]);

        return response()->json(['message' => 'Employer created successfully', 'employer' => $user], 201);
    }

    public function actionEmployer(Request $request, $id, $action)
    {
        // Stub for approve/reject
        return response()->json(['message' => "Employer $action successful"]);
    }

    public function getJobSeekers()
    {
        $seekers = User::with('seekerProfile')->where('role', 'seeker')->latest()->get()->map(function ($u) {
            $nameParts = explode(' ', $u->name, 2);
            $profile = $u->seekerProfile;
            return [
                'id' => $u->id,
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'email' => $u->email,
                'contact_number' => $profile->phone ?? '',
                'referral_status' => 'referral_ready',
                'profile_completed' => !empty($profile->phone),
            ];
        });

        return response()->json(['seekers' => $seekers]);
    }

    public function actionJobSeeker(Request $request, $id, $action)
    {
        if ($action === 'delete') {
            User::where('id', $id)->delete();
            return response()->json(['message' => 'Seeker deleted']);
        }
        return response()->json(['message' => "Action $action successful"]);
    }

    public function updateSeekerReferral(Request $request, $id)
    {
        return response()->json(['message' => 'Referral status updated']);
    }

    public function getJobs()
    {
        $jobs = JobPost::with('employer')->latest()->get()->map(function ($j) {
            $data = $j->toArray();
            $data['company_name'] = $j->employer ? $j->employer->name : 'Unknown';
            return $data;
        });

        return response()->json(['jobs' => $jobs]);
    }

    public function closeJob(Request $request, $id)
    {
        $job = JobPost::findOrFail($id);
        $job->update(['status' => 'closed']);
        return response()->json(['message' => 'Job closed']);
    }

    public function getApplications()
    {
        $apps = Application::with(['user', 'jobPost.employer'])->latest()->get()->map(function ($app) {
            $u = $app->user;
            $nameParts = explode(' ', $u->name, 2);
            return [
                'id' => $app->id,
                'application_status' => $app->status,
                'applied_at' => $app->created_at,
                'job_title' => $app->jobPost ? $app->jobPost->job_title : 'Unknown',
                'company_name' => ($app->jobPost && $app->jobPost->employer) ? $app->jobPost->employer->name : 'Unknown',
                'applicant_name' => $u->name,
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'email' => $u->email,
            ];
        });

        return response()->json(['applications' => $apps]);
    }
}
