<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'photo',
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
}
