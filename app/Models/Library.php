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

    public function getFileSizeFormattedAttribute(): string
    {
        if ($this->pdf_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->pdf_path)) {
            $bytes = \Illuminate\Support\Facades\Storage::disk('public')->size($this->pdf_path);
            if ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 1) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 1) . ' KB';
            }
            return $bytes . ' B';
        }
        return '-';
    }
}
