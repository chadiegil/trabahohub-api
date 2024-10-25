<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_title',
        'description',
        'location',
        'salary',
        'job_type',
        'company_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
