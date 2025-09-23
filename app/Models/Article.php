<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model {
    use HasFactory;
    protected $fillable = ['title', 'publish_place', 'article_pdf', 'conclusion_pdf'];

    public function participants()
    {
        return $this->belongsToMany(
            Participant_new::class,          // Model nomi
            'article_participant_new',   // Pivot jadval nomi
            'article_id',                // Pivotdagi article foreign key
            'participant_new_id'         // Pivotdagi participant foreign key
        );
    }
    public function articleScores()
    {
        return $this->hasMany(ArticleScore::class, 'article_id');
    }
}
