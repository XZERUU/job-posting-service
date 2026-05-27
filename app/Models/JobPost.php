<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPost extends Model
{
    protected $table = 'job_posts';

    protected $fillable = [
        'employer_id',
        'job_title',
        'job_description',
        'job_type',
        'salary_min',
        'salary_max',
        'location',
        'vacancies',
        'requirements',
        'status',
        'posted_at',
        'closing_date'
    ];

    public $timestamps = true;

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Approval',
            'active' => 'Active',
            'rejected' => 'Rejected',
            'closed' => 'Closed',
            default => ucwords(str_replace('_', ' ', (string) $this->status)),
        };
    }

    protected function casts(): array
    {
        return [
            'posted_at' => 'datetime',
            'closing_date' => 'datetime',
        ];
    }

    /**
     * Get the employer that posted the job.
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get the required skills for the job.
     */
    public function requiredSkills(): HasMany
    {
        return $this->hasMany(JobRequiredSkill::class, 'job_post_id');
    }
}
