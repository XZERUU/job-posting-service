<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobPost;
use App\Models\Application;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_seekers' => User::where('role', 'seeker')->count(),
            'total_job_posts' => JobPost::count(),
            'total_applications' => Application::count(),
        ];

        $recent_users = User::latest()->limit(10)->get();
        $recent_job_posts = JobPost::with('employer')->latest()->limit(10)->get();
        $recent_applications = Application::with('user', 'jobPost')->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_job_posts', 'recent_applications'));
    }

    /**
     * Show users management page
     */
    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user detail
     */
    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update user role
     */
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,employer,seeker',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'User role updated successfully.');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    /**
     * Show job posts management
     */
    public function jobPosts()
    {
        $job_posts = JobPost::with('employer')->paginate(15);
        return view('admin.job-posts.index', compact('job_posts'));
    }

    /**
     * Delete job post
     */
    public function destroyJobPost(JobPost $jobPost)
    {
        $jobPost->delete();
        return redirect()->route('admin.job-posts')->with('success', 'Job post deleted successfully.');
    }

    /**
     * Show applications management
     */
    public function applications()
    {
        $applications = Application::with('user', 'jobPost')->paginate(15);
        return view('admin.applications.index', compact('applications'));
    }
}
