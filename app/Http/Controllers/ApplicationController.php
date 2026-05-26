<?php

namespace App\Http\Controllers; // This must match exactly

use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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

    public function edit(Application $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        if ($application->status !== 'pending') {
            return redirect()
                ->route('applications.index')
                ->with('status', 'Only pending applications can be edited.');
        }

        $application->load('jobPost.employer');

        return view('applications.edit', compact('application'));
    }

    public function update(Request $request, Application $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        if ($application->status !== 'pending') {
            return redirect()
                ->route('applications.index')
                ->with('status', 'Only pending applications can be edited.');
        }

        $validated = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:5000'],
        ]);

        $application->update([
            'cover_letter' => $validated['cover_letter'] ?? null,
        ]);

        return redirect()
            ->route('applications.index')
            ->with('status', 'Application updated successfully.');
    }

    public function destroy(Application $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        if ($application->status !== 'pending') {
            return redirect()
                ->route('applications.index')
                ->with('status', 'Only pending applications can be cancelled.');
        }

        $application->delete();

        return redirect()
            ->route('applications.index')
            ->with('status', 'Application cancelled successfully.');
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

        $applicationData = [
            'user_id' => auth()->id(),
            'job_post_id' => $job->id,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => 'pending',
        ];

        // Compatibility for existing local SQLite databases created before job_post_id was added.
        if (Schema::hasColumn('applications', 'job_id')) {
            $applicationData['job_id'] = $job->id;
        }

        Application::create($applicationData);

        return redirect()
            ->route('applications.index')
            ->with('status', 'Application submitted successfully.');
    }
}
