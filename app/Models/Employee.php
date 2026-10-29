<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'photo',
        'turniket_id',
        'turniket_photo',
        'phone',
        'position',
        'birth_date',
        'theme',
        'custom_wish',
        'gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function works()
    {
        return $this->hasMany(EmployeeWork::class);
    }

    /**
     * Get primary display photo:
     * Turniket photo first (as currently requested), otherwise custom birthday photo, otherwise default avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->turniket_photo)) {
            return $this->turniket_photo;
        }

        if (!empty($this->photo)) {
            return str_starts_with($this->photo, 'http') ? $this->photo : asset($this->photo);
        }

        return ($this->gender === 'female') 
            ? asset('assets/images/avatar/female.png') 
            : asset('assets/images/avatar/default.png');
    }
}
