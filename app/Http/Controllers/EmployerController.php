<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Get jobs posted by this employer
        $jobs = JobPost::withCount('applications')
            ->where('employer_id', $user->id)
            ->latest()
            ->get();

        $active_jobs_count = $jobs->where('status', 'active')->count();

        // Get all applications for jobs posted by this employer
        $applications = Application::with('user', 'jobPost')
            ->whereIn('job_post_id', $jobs->pluck('id'))
            ->get();

        $total_applications = $applications->count();
        $pending_applications = $applications->where('status', 'pending')->count();
        $approved_applications = $applications->where('status', 'approved')->count();

        $recent_applications = $applications->sortByDesc('created_at')->take(10);

        return view('employer.dashboard', compact(
            'jobs',
            'active_jobs_count',
            'total_applications',
            'pending_applications',
            'approved_applications',
            'recent_applications'
        ));
    }

    public function applications()
    {
        $jobIds = Auth::user()->jobPosts()->pluck('id');

        $applications = Application::with('user', 'jobPost')
            ->whereIn('job_post_id', $jobIds)
            ->latest()
            ->paginate(15);

        return view('employer.applications.index', compact('applications'));
    }

    public function showApplication(Application $application)
    {
        if ($application->jobPost?->employer_id !== Auth::id()) {
            abort(403);
        }

        $application->load('user.seekerProfile', 'jobPost');

        return view('employer.applications.show', compact('application'));
    }

    public function updateApplicationStatus(Application $application, string $status)
    {
        if ($application->jobPost?->employer_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array($status, ['approved', 'rejected'], true)) {
            abort(404);
        }

        $application->update(['status' => $status]);

        return redirect()
            ->route('employer.applications.show', $application)
            ->with('success', 'Application status updated.');
    }
}
