<?php

namespace App\Models;

use App\Events\SubmissionUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'problem_id',
        'code',
        'language_id',
        'status',
        'verdict',
        'error_message',
        'execution_time',
        'memory_usage',
        'contest_id',
        'points'
    ];

    protected $languages = [
        '54' => 'C++',
        '71' => 'Python',
        '62' => 'Java'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updated(function (Submission $submission) {
            if ($submission->isDirty('status')) {
                event(new SubmissionUpdated($submission));
            }
        });
        
        static::created(function (Submission $submission) {
            if ($submission->status === 'Accepted') {
                event(new SubmissionUpdated($submission));
            }
        });
    }

    /**
     * Get the user that owns the submission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the problem that owns the submission.
     */
    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }

    /**
     * Get the contest that owns the submission.
     */
    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }

    /**
     * Get the language name based on language_id.
     */
    public function getLanguageName(): string
    {
        return $this->languages[$this->language_id] ?? 'Unknown';
    }
} 