<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApplicationApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'job_post_id' => ['required', 'exists:job_posts,id'],
            'cover_letter' => ['nullable', 'string'],
        ]);

        $job = JobPost::findOrFail($request->job_post_id);

        if ($job->status !== 'active') {
            throw ValidationException::withMessages([
                'job_post_id' => ['This job is no longer active.'],
            ]);
        }

        // Check if already applied
        $existing = Application::where('user_id', $request->user()->id)
            ->where('job_post_id', $request->job_post_id)
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'job_post_id' => ['You have already applied for this job.'],
            ]);
        }

        $application = Application::create([
            'user_id' => $request->user()->id,
            'job_post_id' => $request->job_post_id,
            'cover_letter' => $request->cover_letter,
            'status' => 'for_review',
        ]);

        return response()->json([
            'message' => 'Application submitted successfully.',
            'application' => [
                'id' => $application->id,
                'application_status' => $application->status,
                'applied_at' => $application->created_at,
            ],
        ], 201);
    }

    public function myApplications(Request $request)
    {
        $applications = Application::with(['jobPost.employer'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $formatted = $applications->map(function ($app) {
            return [
                'id' => $app->id,
                'job_post_id' => $app->job_post_id,
                'cover_letter' => $app->cover_letter,
                'application_status' => $app->status,
                'applied_at' => $app->created_at,
                // Joined fields expected by mobile
                'job_title' => $app->jobPost ? $app->jobPost->job_title : 'Unknown Job',
                'company_name' => ($app->jobPost && $app->jobPost->employer) ? $app->jobPost->employer->name : 'Unknown Company',
            ];
        });

        return response()->json([
            'applications' => $formatted
        ]);
    }
}
