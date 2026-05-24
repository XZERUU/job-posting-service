<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard with Role Redirection Logic
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'employer') {
            // Redirecting is cleaner than returning a view directly
            return redirect()->route('employer.dashboard');
        }
        return view('dashboard', ['profile' => (object) []]);
    })->name('dashboard');

    // --- Job Board Routes ---
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
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
    // Make sure your 'employer' middleware is alias-registered in bootstrap/app.php
    Route::middleware('employer')->group(function () {
        Route::get('/employer/dashboard', function () {
            return view('employer.dashboard');
        })->name('employer.dashboard');
    });
});

require __DIR__.'/auth.php';