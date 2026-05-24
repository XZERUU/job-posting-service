<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobPost;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        // Fetches all jobs from the database
        $jobs = Job::latest()->paginate(9);

        // Returns the view and passes the $jobs variable
        return view('jobs.index', compact('jobs'));
    }

    public function show($id)
    {
        // This will be useful when you create the job details page
        $job = Job::findOrFail($id);
        return view('jobs.show', compact('job'));
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'job_type' => 'required|in:full-time,part-time,contract,temporary',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'location' => 'required|string|max:255',
            'vacancies' => 'required|integer|min:1',
            'requirements' => 'nullable|string',
            'closing_date' => 'nullable|date|after:today',
        ]);

        $validated['employer_id'] = auth()->id();
        $validated['status'] = 'active';
        $validated['posted_at'] = now();

        JobPost::create($validated);

        return redirect()->route('employer.dashboard')->with('success', 'Job posted successfully!');
    }
}
