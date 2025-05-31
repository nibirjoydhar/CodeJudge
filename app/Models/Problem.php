<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;                                                                                                                                                                                                                                                                                                                                                       
use App\Models\User;
use App\Models\Submission;

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
        'input_format',
        'output_format',
        'constraints',
        'sample_input',
        'sample_output',
        'explanation',
        'difficulty',
        'created_by'
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

    /**
     * Get the submissions for the problem.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Check if the problem is solved by a specific user
     */
    public function isSolvedByUser(?User $user = null): bool
    {
        if (!$user) {
            return false;
        }

        return $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', 'Accepted')
            ->exists();
    }

    public function testCases(): HasMany
    {
        return $this->hasMany(TestCase::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 