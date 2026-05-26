<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeekerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'headline',
        'location',
        'about',
        'skills',
        'education',
        'experiences',
        'resume_path',
        'linkedin_url',
        'portfolio_url',
        'github_url',
    ];

    protected $casts = [
        'skills' => 'array',
        'education' => 'array',
        'experiences' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
