<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant_new extends Model {
    use HasFactory;

    protected $table = 'participants_new';
    protected $fillable = ['full_name', 'position', 'degree'];

    public function articles()
    {
        return $this->belongsToMany(
            Article::class,
            'article_participant_new',
            'participant_new_id',
            'article_id'
        );
    }
}
