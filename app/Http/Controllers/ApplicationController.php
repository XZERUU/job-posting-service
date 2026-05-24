<?php

namespace App\Http\Controllers; // This must match exactly

use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('applications.index');
    }
}