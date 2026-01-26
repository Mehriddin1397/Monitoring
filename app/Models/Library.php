<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'author',
        'pdf_path'
    ];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }
}
