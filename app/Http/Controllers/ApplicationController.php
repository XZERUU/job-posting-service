<?php

namespace App\Http\Controllers; // This must match exactly

use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with('jobPost.employer')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('applications.index', compact('applications'));
    }

    public function store(Request $request, JobPost $job)
    {
        if (! auth()->user()->isSeeker()) {
            return back()->with('error', 'Only job seekers can submit applications.');
        }

        $validated = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:5000'],
        ]);

        $alreadyApplied = Application::where('user_id', auth()->id())
            ->where('job_post_id', $job->id)
            ->exists();

        if ($alreadyApplied) {
            return redirect()
                ->route('applications.index')
                ->with('status', 'You have already applied for this job.');
        }

        Application::create([
            'user_id' => auth()->id(),
            'job_post_id' => $job->id,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('applications.index')
            ->with('status', 'Application submitted successfully.');
    }
}
