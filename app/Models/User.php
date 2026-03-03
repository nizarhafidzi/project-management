<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'aps_access_token',
        'aps_refresh_token',
        'aps_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'aps_access_token',
        'aps_refresh_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aps_token_expires_at' => 'datetime',
        ];
    }

    /**
     * Projects this user is assigned to.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(\Modules\Project\Models\Project::class, 'project_user')
            ->withPivot('role_in_project')
            ->withTimestamps();
    }

    /**
     * Daily logs created by this user.
     */
    public function dailyLogs(): HasMany
    {
        return $this->hasMany(\Modules\Operations\Models\DailyLog::class);
    }
}
