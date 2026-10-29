<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }

    public function isPdf(): bool
    {
        return $this->extension === 'pdf';
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if ($this->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->file_path)) {
            $bytes = \Illuminate\Support\Facades\Storage::disk('public')->size($this->file_path);
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
