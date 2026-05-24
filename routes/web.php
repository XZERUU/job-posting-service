<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard with Role Redirection Logic
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->role === 'employer') {
            return redirect()->route('employer.dashboard');
        }
        return view('dashboard', ['profile' => (object) []]);
    })->name('dashboard');

    // --- Job Board Routes ---
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');

    // --- Job Employer Routes (must come before {id}) ---
    Route::middleware('employer')->group(function () {
        Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    });

    Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');

    // --- Job Seeker Profile ---
    Route::get('/profile', [ProfileController::class, 'show'])->name('seeker.profile');

    // --- Account Settings (Breeze) ---
    Route::prefix('settings')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::patch('/profile/custom', [ProfileController::class, 'updateCustom'])->name('profile.update-custom');
    });

    // --- Employer Specific Routes ---
    Route::middleware('employer')->group(function () {
        Route::get('/employer/dashboard', [EmployerController::class, 'dashboard'])->name('employer.dashboard');
    });

    // --- Admin Specific Routes ---
    Route::middleware('admin')->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            // Users Management
            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
            Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update-role');
            Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

            // Job Posts Management
            Route::get('/job-posts', [AdminController::class, 'jobPosts'])->name('job-posts');
            Route::delete('/job-posts/{jobPost}', [AdminController::class, 'destroyJobPost'])->name('job-posts.destroy');

            // Applications Management
            Route::get('/applications', [AdminController::class, 'applications'])->name('applications');
        });
    });
});

require __DIR__.'/auth.php';
