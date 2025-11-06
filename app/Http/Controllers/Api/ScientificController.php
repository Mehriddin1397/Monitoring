<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScientificController extends Controller
{
    // 1️⃣ Ilmiy xodimlar ro‘yxati
    public function index(Request $request)
    {
        $scientists = User::where('is_scientific', 1)
            ->withCount('articles')
            ->with(['articles.articleScore'])
            ->get()
            ->map(function ($scientist) {
                $totalScore = $scientist->articles->sum(function ($article) {
                    return optional($article->articleScore)->total_score ?? 0;
                });

                $scientist->total_score = $totalScore;
                return $scientist;
            })
            ->sortByDesc('total_score')
            ->values();

        return response()->json([
            'data' => $scientists,
        ]);
    }


    // 2️⃣ Bitta xodimning maqolalari
    public function articles($id)
    {
        $scientist = User::with(['articles.articleScore'])->findOrFail($id);

        $articles = $scientist->articles->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'pdf' => $article->article_pdf,
                'definitions' => optional($article->articleScore)->definitions ?? 0,
                'classifications'=> optional($article->articleScore)->classifications ?? 0,
                'suggestions'=> optional($article->articleScore)->suggestions ?? 0,
                'total_score' => optional($article->articleScore)->total_score ?? 0,
                'publish_place' => $article->publish_place,
                'status' => $article->status,
            ];
        });

        return response()->json([
            'scientist' => $scientist->full_name,
            'articles' => $articles
        ]);
    }

}
