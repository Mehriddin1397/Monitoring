<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{


    protected $fillable = [
        'user_id',
        'suggestion',
    ];

    // User bilan bog'lash
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
