<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OngoingWork extends Model
{
    protected $fillable = [
        'project_name',
        'problems',
        'process',
        'process_color',
        'remaining_time',
        'deadline',

    ];
}
