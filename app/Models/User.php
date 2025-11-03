<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Jobs\CheckUserBadges;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'dob',
        'email',
        'password',
        'role',
        'gender',
        'phone',
        'location',
        'avatar',
<<<<<<< HEAD
        'points',
=======
        'google_id',
>>>>>>> 4909630da791fe0bc6fe51e52e93f09824143416
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'points' => 'integer',
            'role' => 'string',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the contributions for the user.
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    /**
     * Get the chats for the user.
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    /**
     * Get the rewards for the user.
     */
    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class);
    }

    /**
     * Get the user badges for the user.
     */
    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * Check if user has a specific badge
     */
    public function hasBadge(Badge $badge): bool
    {
        return $this->userBadges()->where('badge_id', $badge->id)->exists();
    }

    /**
     * Get all badges the user has earned
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('awarded_at')
            ->orderBy('points_required');
    }

    /**
     * Add points to the user and check for new badges
     */
    public function addPoints(int $points): void
    {
        $this->increment('points', $points);
        $this->fresh();
        // Dispatch job to check for new badges
        CheckUserBadges::dispatch($this);
    }

    public function is_admin(): bool
    {
        return $this->role === 'admin';
    }
}
