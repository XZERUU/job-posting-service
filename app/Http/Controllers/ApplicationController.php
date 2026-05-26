<?php

namespace App\Http\Controllers; // This must match exactly

use App\Support\SeekerData;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('applications.index', [
            'applications' => SeekerData::applicationRows(Auth::id(), 50),
        ]);
    }
}
