<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{
    public function dashboard()
    {
        // Get jobs posted by this employer
        $jobs = Job::where('user_id', Auth::id())->latest()->get();
        
        // Count applications for these jobs (assuming you have an Application model)
        // For now, let's just pass the jobs to the view
        return view('employer.dashboard', compact('jobs'));
    }
}