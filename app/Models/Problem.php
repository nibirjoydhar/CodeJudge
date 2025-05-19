<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Problem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'test_cases',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'test_cases' => 'json',
        ];
    }

    /**
     * Get the formatted test cases.
     * 
     * @return array
     */
    public function getFormattedTestCasesAttribute(): array
    {
        return $this->test_cases ?? [];
    }
    
    /**
     * Get the comments for the problem.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
} 