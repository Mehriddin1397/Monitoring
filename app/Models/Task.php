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
    ];


    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUsers() {
        return $this->belongsToMany(User::class, 'task_user');
    }

}
