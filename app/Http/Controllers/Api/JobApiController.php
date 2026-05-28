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

        $user = $request->user();
        $canView = $job->status === 'active'
            || $user?->role === 'admin'
            || ($user?->role === 'employer' && $job->employer_id === $user->id);

        if (! $canView) {
            abort(404);
        }

        $data = $job->toArray();
        $data['company_name'] = $job->employer ? $job->employer->name : 'Unknown';
        
        if ($app = $job->applications->first()) {
            $appData = $app->toArray();
            $appData['application_status'] = $app->status;
            $appData['applied_at'] = $app->created_at;
            $data['my_application'] = $appData;
        } else {
            $data['my_application'] = null;
        }

        return response()->json([
            'job' => $data
        ]);
    }

}
