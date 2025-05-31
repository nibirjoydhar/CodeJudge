<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestCase extends Model
{
    protected $fillable = [
        'problem_id',
        'input',
        'expected_output',
        'is_sample',
        'points'
    ];

    protected $casts = [
        'is_sample' => 'boolean',
        'points' => 'integer'
    ];

    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }
} 