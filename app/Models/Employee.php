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
     * Turniket photo first, then custom birthday photo, otherwise high-tech SVG gradient avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->turniket_photo)) {
            return $this->turniket_photo;
        }

        if (!empty($this->photo)) {
            return str_starts_with($this->photo, 'http') ? $this->photo : asset($this->photo);
        }

        // Initials avatar
        $initials = 'X';
        if (!empty($this->full_name)) {
            $parts = preg_split('/\s+/u', trim($this->full_name));
            $p1 = mb_substr($parts[0] ?? '', 0, 1);
            $p2 = mb_substr($parts[1] ?? '', 0, 1);
            $initials = mb_strtoupper($p1 . $p2);
        }

        $c1 = ($this->gender === 'female') ? '%23ec4899' : '%234f46e5';
        $c2 = ($this->gender === 'female') ? '%23f43f5e' : '%237c3aed';

        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 128 128'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='{$c1}'/%3E%3Cstop offset='100%25' stop-color='{$c2}'/%3E%3C/defs%3E%3Crect width='128' height='128' rx='64' fill='url(%23g)'/%3E%3Ctext x='50%25' y='55%25' font-family='sans-serif' font-size='46' font-weight='700' fill='%23ffffff' text-anchor='middle' dominant-baseline='middle'%3E{$initials}%3C/text%3E%3C/svg%3E";
    }
}
