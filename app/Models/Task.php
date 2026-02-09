<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'document',
        'start_date',
        'end_date',
        'created_by',
        'assigned_users',
        'status',
        'task_type',
    ];


    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUsers() {
        return $this->belongsToMany(User::class, 'task_user');
    }


    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }


    public function statuses()
    {
        return $this->hasMany(TaskStatus::class);
    }

}
