<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pro_document extends Model
{
    protected $fillable = [
        'file_path',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
