<?php

namespace App\Http\Controllers;

use App\Support\SeekerData;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $profile = SeekerData::profile($user->id);
        $strength = SeekerData::profileStrength($user, $profile);

        return view('dashboard', [
            'stats' => SeekerData::stats($user->id),
            'recentApplications' => SeekerData::applicationRows($user->id),
            'recommendedJobs' => SeekerData::recommendedJobs($user->id),
            'profileCompletion' => $strength['percent'],
            'completionChecklist' => $strength['checklist'],
        ]);
    }
}
