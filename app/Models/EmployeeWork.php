<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeWork extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'title',
        'content',
        'excerpt',
        'layout_theme',
        'cover_image',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'poem' => "Шеър",
            'story' => "Ҳикоя / Бадиа",
            'scientific' => "Илмий таъриф / Мақола",
            'quote' => "Ҳикматли сўз / Иқтибос",
            default => "Ижодий иш"
        };
    }

    public function getThemeNameAttribute()
    {
        return match($this->layout_theme) {
            'lyric' => "Лирик шеърият",
            'book' => "Китобий / Насрий",
            'academic' => "Илмий академик",
            'card' => "Замонавий карта",
            default => "Стандарт"
        };
    }
}
