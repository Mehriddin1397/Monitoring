<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\ArticleScore;
use App\Models\Participant_new;
use Illuminate\Http\Request;

class ArticleController extends Controller {
    public function index() {
        $participants = Participant_new::all();
        $articles = Article::with('participants')->get();
        return view('admin.articles.index', compact('participants'));
    }

    public function show(Participant_new $participant_new) {

        dd($participant_new);
        $participants = Participant_new::all();
        // Faqat tanlangan participant va unga tegishli maqolalar + maqolalarning ballari
        $articles = $participant_new->articles()->with('articleScores')->get();

        return view('admin.articles.index', [
            'participant' => $participant_new,
            'articles' => $articles,
            'participants' => $participants
        ]);
    }


    public function store(Request $request) {
        $article = Article::create([
            'title' => $request->title,
            'publish_place' => $request->publish_place,
            'article_pdf' => $request->file('article_pdf')?->store('articles', 'public'),
            'conclusion_pdf' => $request->file('conclusion_pdf')?->store('conclusions', 'public')
        ]);

        // Faqat bitta marta attach qilish
        $article->participants()->attach($request->participants);

        $total_score = ($request->definitions * 0.6) +
            ($request->classifications * 0.4) +
            ($request->suggestions * 0.2);

        ArticleScore::create([
            'article_id' => $article->id,
            'definitions' => $request->definitions,
            'classifications' => $request->classifications,
            'suggestions' => $request->suggestions,
            'total_score' => $total_score,
        ]);

        return redirect()->back();
    }




    public function update(Request $request, Article $article) {
        $article->update([
            'title' => $request->title,
            'publish_place' => $request->publish_place
        ]);

        if ($request->file('article_pdf')) {
            $article->update(['article_pdf' => $request->file('article_pdf')->store('articles')]);
        }
        if ($request->file('conclusion_pdf')) {
            $article->update(['conclusion_pdf' => $request->file('conclusion_pdf')->store('conclusions')]);
        }

        $article->participants()->sync($request->participants);
        return redirect()->route('articles.index');
    }

    public function destroy(Article $article) {
        $article->delete();
        return redirect()->back();
    }


}
