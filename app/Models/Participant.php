<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'name',
        'project_id',
        'type',

    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
