<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannedWork extends Model
{
    protected $fillable = [
        'project_name',
        'required_expenses',
        'preparation_time',
        'performance_results',
        'required_amount',
    ];
}
