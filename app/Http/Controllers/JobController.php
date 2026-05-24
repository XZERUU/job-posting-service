<?php

namespace App\Http\Controllers;

use App\Models\Job; 
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
}