<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'password',
        'created_by'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function problems(): BelongsToMany
    {
        return $this->belongsToMany(Problem::class, 'contest_problems');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'contest_participants')
            ->withTimestamp('joined_at');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive()
    {
        $now = now();
        return $this->start_time <= $now && $now <= $this->end_time;
    }

    public function hasEnded()
    {
        return now() > $this->end_time;
    }

    public function hasStarted()
    {
        return now() >= $this->start_time;
    }
} 