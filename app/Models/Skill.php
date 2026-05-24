<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = 'skills';

    protected $fillable = [
        'skill_name',
        'category'
    ];

    // Table only has created_at, no updated_at
    public $timestamps = false;
}
