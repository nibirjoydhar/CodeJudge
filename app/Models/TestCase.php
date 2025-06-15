<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'problem_id',
        'input',
        'expected_output',
        'is_sample',
        'points',
    ];

    protected $casts = [
        'is_sample' => 'boolean', // Cast to boolean for easier handling
    ];

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }
}