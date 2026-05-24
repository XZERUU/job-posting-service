<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobRequiredSkill extends Model
{
    protected $table = 'job_required_skills';

    protected $fillable = [
        'job_post_id',
        'skill_id',
        'required_level',
        'is_required'
    ];

    // Table only has created_at, no updated_at
    public $timestamps = false;

    /**
     * Get the job post that requires this skill.
     */
    public function jobPost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    /**
     * Get the skill details.
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
