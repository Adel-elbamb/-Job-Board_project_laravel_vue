<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'responsibilities',
        'qualifications',
        'salary_from',
        'salary_to',
        'location',
        'job_type',
        'deadline',
        'company_logo',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
