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
        $totalScore = $article->articleScore->avg('total_score'); // o‘rtacha baho
        $definitions = $article->articleScore->avg('definitions'); // o‘rtacha baho
        $classifications = $article->articleScore->avg('classifications'); // o‘rtacha baho
        $suggestions = $article->articleScore->avg('suggestions'); // o‘rtacha baho

        return response()->json([
            'id' => $article->id,
            'title' => $article->title,
            'pdf' => $article->conclusion_pdf,
            'scores' => [
                'definitions' => $definitions ?? 0,
                'classifications' => $classifications ?? 0,
                'suggestions' => $suggestions ?? 0,
                'total_score' => $totalScore ?? 0,
            ]
        ]);
    }
}
