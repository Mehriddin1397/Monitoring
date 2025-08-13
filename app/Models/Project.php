<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'file_buyruq',
        'file_qushimcha',
        'pro_bos_name',
        'tel_number',
        'job',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
