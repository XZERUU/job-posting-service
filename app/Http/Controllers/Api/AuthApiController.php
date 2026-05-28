<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SeekerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:job_seeker,employer'],
            'name' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'profile.first_name' => ['nullable', 'string', 'max:255'],
            'profile.last_name' => ['nullable', 'string', 'max:255'],
            'profile.contact_number' => ['nullable', 'string', 'max:255'],
        ]);

        $role = $request->role === 'job_seeker' ? 'seeker' : 'employer';
        
        $firstName = $request->input('first_name') ?: $request->input('profile.first_name', '');
        $lastName = $request->input('last_name') ?: $request->input('profile.last_name', '');
        
        $name = $request->input('name') ?: $request->input('company_name') ?: trim($firstName . ' ' . $lastName);
        if (empty($name)) {
            $name = 'User'; // Fallback
        }

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        if ($role === 'seeker') {
            SeekerProfile::create([
                'user_id' => $user->id,
                'phone' => $request->input('profile.contact_number', null),
            ]);
        }

        $token = $user->createToken('peso_token')->plainTextToken;

        // Map role back to what mobile expects
        $userResponse = $user->toArray();
        if ($userResponse['role'] === 'seeker') {
            $userResponse['role'] = 'job_seeker';
        }

        return response()->json([
            'token' => $token,
            'user' => $userResponse,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('peso_token')->plainTextToken;

        $userResponse = $user->toArray();
        if ($userResponse['role'] === 'seeker') {
            $userResponse['role'] = 'job_seeker';
        }

        return response()->json([
            'token' => $token,
            'user' => $userResponse,
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        
        $userResponse = $user->toArray();
        if ($userResponse['role'] === 'seeker') {
            $userResponse['role'] = 'job_seeker';
        }

        return response()->json([
            'user' => $userResponse,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
