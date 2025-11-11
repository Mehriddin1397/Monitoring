<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Maxsus foydalanuvchilar (masalan, admin yoki tekshiruvchi)
        $specialUsers = [59, 2]; // bu yerga kerakli user_id larni qo‘shish mumkin

        if (in_array($user->id, $specialUsers) || $user->role === 'admin') {
            $articles = Article::with(['user', 'articleScore'])->latest()->get();
        } else {
            $articles = Article::with(['user', 'articleScore'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('admin.articles.index', compact('articles', 'user'));
    }

    /**
     * Xodim maqola yuklaydi
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'publish_place' => 'required|string',
            'article_pdf' => 'required|mimes:pdf|max:20488',
        ]);

        // PDF yuklash
        $path = $request->file('article_pdf')->store('articles', 'public');

        Article::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'publish_place' => $request->publish_place,
            'article_pdf' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Maqola muvaffaqiyatli yuklandi va tekshiruvda.');
    }

    /**
     * Boshliq maqolani tekshiradi va baholaydi
     */
    public function check(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'definitions' => 'required|integer|min:0',
            'classifications' => 'required|integer|min:0',
            'suggestions' => 'required|integer|min:0',
            'conclusion_pdf' => 'required|mimes:pdf|max:20488',
        ]);

        $article = Article::findOrFail($request->article_id);

        // PDF faylni yuklash
        $pdfPath = $request->file('conclusion_pdf')->store('conclusions', 'public');

        // Bahoni hisoblash
        $total_score = ($request->definitions * 0.6) +
            ($request->classifications * 0.4) +
            ($request->suggestions * 0.2);

        // ArticleScore yozuvi
        ArticleScore::create([
            'article_id' => $article->id,
            'definitions' => $request->definitions,
            'classifications' => $request->classifications,
            'suggestions' => $request->suggestions,
            'total_score' => $total_score,
        ]);

        // Article statusini yangilash
        $article->update([
            'conclusion_pdf' => $pdfPath,
            'status' => 'checked',
            'checked_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Maqola tekshirildi va baho qo‘yildi.',
        ]);
    }

    /**
     * Maqolani yangilash (masalan sarlavha yoki joylash joyini)
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'publish_place' => 'required|string',
        ]);

        $article->update([
            'title' => $request->title,
            'publish_place' => $request->publish_place,
        ]);

        return back()->with('success', 'Maqola ma’lumotlari yangilandi.');
    }

    /**
     * Foydalanuvchi yoki boshliq maqolani ko‘radi
     */
    public function show($id)
    {
        $article = Article::with(['user', 'checkedBy', 'articleScores'])->findOrFail($id);
        return view('articles.show', compact('article'));
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        // Faqat maqola muallifi yoki admin o‘chira oladi
        if (auth()->id() !== $article->user_id && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Sizga bu maqolani o‘chirishga ruxsat berilmagan.');
        }

        // Fayllar mavjud bo‘lsa, o‘chirish
        if ($article->article_pdf && file_exists(public_path('uploads/articles/' . $article->article_pdf))) {
            unlink(public_path('uploads/articles/' . $article->article_pdf));
        }

        if ($article->conclusion_pdf && file_exists(public_path('uploads/articles/' . $article->conclusion_pdf))) {
            unlink(public_path('uploads/articles/' . $article->conclusion_pdf));
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Maqola muvaffaqiyatli o‘chirildi!');
    }



}
