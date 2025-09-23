<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'definitions',
        'classifications',
        'suggestions',
        'total_score'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
