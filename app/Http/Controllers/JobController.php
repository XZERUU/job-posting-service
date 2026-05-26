<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->search);
        $profile = $request->user()->seekerProfile;

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
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $suggestionTerms = collect(explode(' ', (string) ($profile->headline ?? '')))
            ->map(fn ($term) => trim($term))
            ->filter(fn ($term) => strlen($term) >= 3)
            ->take(5)
            ->values();

        $recommendedJobs = collect();

        if ($suggestionTerms->isNotEmpty()) {
            $recommendedJobs = JobPost::with('employer')
                ->where('status', 'active')
                ->where(function ($query) use ($suggestionTerms) {
                    foreach ($suggestionTerms as $term) {
                        $query->orWhere('job_title', 'like', "%{$term}%")
                            ->orWhere('job_description', 'like', "%{$term}%")
                            ->orWhere('requirements', 'like', "%{$term}%");
                    }
                })
                ->latest()
                ->limit(3)
                ->get();
        }

        if ($recommendedJobs->isEmpty()) {
            $recommendedJobs = JobPost::with('employer')
                ->where('status', 'active')
                ->latest()
                ->limit(3)
                ->get();
        }

        return view('jobs.index', compact('jobs', 'recommendedJobs', 'search'));
    }

    public function show(JobPost $job)
    {
        $user = auth()->user();

        if (
            $job->status !== 'active'
            && $job->employer_id !== $user->id
            && $user->role !== 'admin'
        ) {
            abort(404);
        }

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
        $validated['status'] = 'pending';
        $validated['posted_at'] = now();

        JobPost::create($validated);

        return redirect()->route('employer.dashboard')->with('success', 'Job submitted for admin review.');
    }
}
