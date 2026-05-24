<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model
{
    protected $table = 'employers';

    protected $fillable = [
        'user_id',
        'company_name',
        'company_address',
        'contact_person',
        'contact_number',
        'business_type',
        'company_size',
        'approval_status',
        'approved_by',
        'approved_at'
    ];

    public $timestamps = true;

    /**
     * Get the job posts for the employer.
     */
    public function jobPosts(): HasMany
    {
        return $this->hasMany(JobPost::class, 'employer_id');
    }
}
