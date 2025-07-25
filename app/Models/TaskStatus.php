<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    protected $fillable = ['task_id', 'status'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
