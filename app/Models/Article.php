<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model {
    use HasFactory;
    protected $fillable = [
        'title',
        'publish_place',
        'article_pdf',
        'conclusion_pdf',
        'status',
        'user_id',
        'checked_by',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Maqolani tekshirgan foydalanuvchi (boshliq)
     */
    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    /**
     * Maqola uchun baholar
     */
    public function articleScore()
    {
        return $this->hasMany(ArticleScore::class, 'article_id');
    }
}
