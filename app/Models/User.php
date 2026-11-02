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
        return in_array($this->role, ['admin', 'kadr', 'boshliq']);
    }

    public function canViewTurniket(): bool
    {
        return in_array($this->role, ['admin', 'kadr', 'boshliq']);
    }

    public function canManageTurniket(): bool
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
     * Bog'langan xodim profili (user_id yoki ism bo'yicha)
     */
    public function getEmployeeProfileAttribute(): ?Employee
    {
        $emp = $this->employee;
        if (!$emp && !empty($this->name)) {
            $emp = Employee::where('full_name', $this->name)->first();
        }
        return $emp;
    }

    /**
     * Get display avatar for user:
     * Turniket photo first, then custom photo, otherwise fallback to old default avatar (8214212.png)
     */
    public function getAvatarUrlAttribute(): string
    {
        $emp = $this->employee_profile;
        if ($emp) {
            if (!empty($emp->turniket_photo)) {
                return $emp->turniket_photo;
            }
            if (!empty($emp->photo)) {
                return str_starts_with($emp->photo, 'http') ? $emp->photo : asset($emp->photo);
            }
        }

        return asset('assets/images/8214212.png');
    }

    /**
     * Tug'ilgan kuni (chiroyli formatda va yoshi bilan)
     */
    public function getFormattedBirthDateAttribute(): string
    {
        $emp = $this->employee_profile;
        if ($emp && !empty($emp->birth_date)) {
            try {
                $c = \Carbon\Carbon::parse($emp->birth_date);
                return $c->format('d.m.Y') . ' (' . $c->age . ' ёш)';
            } catch (\Exception $e) {
                return (string) $emp->birth_date;
            }
        }

        return 'Кўрсатилмаган';
    }

    /**
     * Foydalanuvchining I-index bo'yicha umumiy to'plagan bahosi/bali
     */
    public function getIIndexScoreAttribute(): float
    {
        $score = $this->articles()
            ->join('article_scores', 'article_scores.article_id', '=', 'articles.id')
            ->sum('article_scores.total_score');

        return round((float) $score, 2);
    }

}
