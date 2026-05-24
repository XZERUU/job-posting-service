<?php

namespace App\Models;

// THIS LINE IS CRITICAL:
use Illuminate\Database\Eloquent\Model; 

class Job extends Model
{
    protected $table = 'job_listings'; 

    protected $fillable = [
        'title', 'company_name', 'location', 'salary', 'type', 'description'
    ];
}