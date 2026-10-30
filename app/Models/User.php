<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'role','auth_code',
        'full_name','position','degree','tel_number','is_scientific',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKadr(): bool
    {
        return $this->role === 'kadr';
    }

    public function isKotib(): bool
    {
        return $this->role === 'kotib';
    }

    public function isBoshliq(): bool
    {
        return $this->role === 'boshliq';
    }

    public function canViewCctv(): bool
    {
        return in_array($this->role, ['admin', 'kadr']);
    }

    public function canScoreArticles(): bool
    {
        return in_array($this->role, ['admin', 'kotib', 'boshliq']) || in_array($this->id, [59, 2]);
    }

    public function createdTasks() {
        return $this->hasMany(Task::class, 'created_by');
    }


    public function assignedTasks() {
        return $this->belongsToMany(Task::class, 'task_user');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function articles()
    {
        return $this->hasMany(\App\Models\Article::class, 'user_id');
    }

    public function taskFiles()
    {
        return $this->hasMany(TaskFile::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    /**
     * Get display avatar for user
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->employee && !empty($this->employee->avatar_url)) {
            return $this->employee->avatar_url;
        }

        $initials = 'X';
        if (!empty($this->name)) {
            $parts = preg_split('/\s+/u', trim($this->name));
            $p1 = mb_substr($parts[0] ?? '', 0, 1);
            $p2 = mb_substr($parts[1] ?? '', 0, 1);
            $initials = mb_strtoupper($p1 . $p2);
        }

        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 128 128'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%234f46e5'/%3E%3Cstop offset='100%25' stop-color='%237c3aed'/%3E%3C/defs%3E%3Crect width='128' height='128' rx='64' fill='url(%23g)'/%3E%3Ctext x='50%25' y='55%25' font-family='sans-serif' font-size='46' font-weight='700' fill='%23ffffff' text-anchor='middle' dominant-baseline='middle'%3E{$initials}%3C/text%3E%3C/svg%3E";
    }

}
