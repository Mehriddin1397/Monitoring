<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleApiController extends Controller
{
    // 3️⃣ Maqola haqida to‘liq ma’lumot
    public function show($id)
    {
        $article = Article::with('articleScore')->findOrFail($id);

        $score = $article->articleScore; // endi object

        return response()->json([
            'id' => $article->id,
            'title' => strip_tags($article->title),
            'publish_place' => strip_tags($article->publish_place),
            'status' => $article->status,
            'pdf' => $article->article_pdf ? url('storage/' . $article->article_pdf) : null,
            'user_id'=>$article->user_id,
            'scores' => [
                'definitions' => $score->definitions ?? 0,
                'classifications' => $score->classifications ?? 0,
                'suggestions' => $score->suggestions ?? 0,
                'total_score' => $score->total_score ?? 0,
            ]
        ], 200);
    }
}
