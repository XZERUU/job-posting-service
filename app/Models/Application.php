<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    // Define the table if it's not the plural of the model name
    // protected $table = 'applications';

    // Mass-assignable attributes
    protected $fillable = [
        'user_id',
        'job_post_id',
        'cover_letter',
        'status',
    ];

    // If you use timestamps (created_at, updated_at), keep this as true
    public $timestamps = true;

    // Relationships
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
