<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'images',
        'published_date',
        'order',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'published_date' => 'date',
    ];

    // Yordamchi accessor: agar rasm bo'lmasa yoki bo'lsa
    public function getFirstImageAttribute()
    {
        if (is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }
        return null;
    }
}
