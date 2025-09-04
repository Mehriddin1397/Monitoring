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
        'manba',
        'izoh',
        'user_id',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function pro_documents()
    {
        return $this->hasMany(Pro_document::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
