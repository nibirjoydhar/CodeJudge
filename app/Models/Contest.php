<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
            ->withTimestamps();
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
        $now = Carbon::now();
        return $this->start_time <= $now && $now <= $this->end_time;
    }

    public function hasEnded()
    {
        return Carbon::now() > $this->end_time;
    }

    public function hasStarted()
    {
        return Carbon::now() >= $this->start_time;
    }

    public function getStatus()
    {
        $now = Carbon::now();
        if ($now < $this->start_time) {
            return 'Upcoming';
        } elseif ($now >= $this->start_time && $now <= $this->end_time) {
            return 'Running';
        } else {
            return 'Ended';
        }
    }

    public function canAccess(User $user)
    {
        // Contest creator can always access
        if ($user->id === $this->created_by) {
            return true;
        }

        // Check if user is a participant
        $isParticipant = $this->participants()->where('user_id', $user->id)->exists();

        // If contest hasn't started, participants can see basic info but not problems
        if (!$this->hasStarted()) {
            return $isParticipant;
        }

        // If contest is running or has ended, participants can access everything
        return $isParticipant;
    }

    public function canSubmit(User $user)
    {
        // Must be a participant and contest must be running
        return $this->participants()->where('user_id', $user->id)->exists() 
            && $this->isActive();
    }

    public function getRankings()
    {
        $rankings = $this->participants()
            ->withCount(['submissions as accepted_count' => function ($query) {
                $query->where('status', 'Accepted')
                    ->whereIn('problem_id', $this->problems->pluck('id'))
                    ->where('created_at', '>=', $this->start_time)
                    ->where('created_at', '<=', $this->end_time);
            }])
            ->withSum(['submissions as total_points' => function ($query) {
                $query->whereIn('problem_id', $this->problems->pluck('id'))
                    ->where('created_at', '>=', $this->start_time)
                    ->where('created_at', '<=', $this->end_time);
            }], 'points')
            ->orderByDesc('total_points')
            ->orderByDesc('accepted_count')
            ->get();

        return $rankings;
    }
} 