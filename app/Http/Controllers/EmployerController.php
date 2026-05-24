<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobPost;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Get jobs posted by this employer
        $jobs = JobPost::where('employer_id', $user->id)->latest()->get();

        $active_jobs_count = $jobs->count();

        // Get all applications for jobs posted by this employer
        $applications = Application::whereIn('job_post_id', $jobs->pluck('id'))->get();

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
}
