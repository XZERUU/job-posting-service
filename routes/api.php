<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\JobApiController;
use App\Http\Controllers\Api\ApplicationApiController;
use App\Http\Controllers\Api\SeekerApiController;
use App\Http\Controllers\Api\EmployerApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\NotificationApiController;

// Public Auth routes
Route::post('/auth/register', [AuthApiController::class, 'register']);
Route::post('/auth/login', [AuthApiController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth & Generic
    Route::get('/auth/me', [AuthApiController::class, 'me']);
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Public/Seeker generic read access (jobs and skills)
    Route::get('/jobs', [JobApiController::class, 'index']);
    Route::get('/jobs/{id}', [JobApiController::class, 'show']);
    Route::get('/jobs/{id}/match', [JobApiController::class, 'match']);
    Route::get('/skills', [SeekerApiController::class, 'getSkills']);

    // Seeker specific actions
    Route::post('/applications', [ApplicationApiController::class, 'store']);
    Route::get('/applications/my-applications', [ApplicationApiController::class, 'myApplications']);
    
    Route::get('/job-seeker/profile', [SeekerApiController::class, 'getProfile']);
    Route::post('/job-seeker/profile', [SeekerApiController::class, 'updateProfile']);
    Route::post('/job-seeker/skills', [SeekerApiController::class, 'updateSkills']);

    // Notifications (stubbed)
    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationApiController::class, 'markRead']);

    // Employer only routes
    Route::middleware('employer')->group(function () {
        Route::get('/employer/profile', [EmployerApiController::class, 'getProfile']);
        Route::get('/employer/jobs', [EmployerApiController::class, 'getJobs']);
        Route::post('/employer/jobs', [EmployerApiController::class, 'storeJob']);
        Route::put('/employer/jobs/{id}', [EmployerApiController::class, 'updateJob']);
        Route::put('/employer/jobs/{id}/close', [EmployerApiController::class, 'closeJob']);
        Route::get('/employer/jobs/{id}/applicants', [EmployerApiController::class, 'getApplicants']);
        Route::put('/employer/applications/{id}/status', [EmployerApiController::class, 'updateApplicationStatus']);
    });

    // Admin only routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/stats', [AdminApiController::class, 'getStats']);
        Route::get('/admin/employers', [AdminApiController::class, 'getEmployers']);
        Route::get('/admin/employers/pending', [AdminApiController::class, 'getPendingEmployers']);
        Route::post('/admin/employers', [AdminApiController::class, 'storeEmployer']);
        Route::put('/admin/employers/{id}/{action}', [AdminApiController::class, 'actionEmployer']);
        
        Route::get('/admin/job-seekers', [AdminApiController::class, 'getJobSeekers']);
        Route::put('/admin/job-seekers/{id}/{action}', [AdminApiController::class, 'actionJobSeeker']);
        Route::put('/admin/job-seekers/{id}/referral-status', [AdminApiController::class, 'updateSeekerReferral']);
        
        Route::get('/admin/jobs', [AdminApiController::class, 'getJobs']);
        Route::put('/admin/jobs/{id}/close', [AdminApiController::class, 'closeJob']);
        Route::get('/admin/applications', [AdminApiController::class, 'getApplications']);
    });
});
