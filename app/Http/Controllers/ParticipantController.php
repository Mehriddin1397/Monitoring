<?php
namespace App\Http\Controllers;
use App\Models\Participant_new;
use Illuminate\Http\Request;

class ParticipantController extends Controller {
    public function index()
    {
        $participants = Participant_new::select('participants_new.*')
            ->selectSub(function ($query) {
                $query->from('article_participant_new')
                    ->join('articles', 'article_participant_new.article_id', '=', 'articles.id')
                    ->join('article_scores', 'articles.id', '=', 'article_scores.article_id')
                    ->whereColumn('article_participant_new.participant_new_id', 'participants_new.id')
                    ->selectRaw('COALESCE(SUM(article_scores.total_score), 0)');
            }, 'total_score_sum')
            ->orderByDesc('total_score_sum')
            ->get();

        return view('admin.participants.index', compact('participants'));
    }

    public function show($id) {
        $participant_new = Participant_new::find($id);
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
        Participant_new::create($request->only('full_name','position','degree'));
        return redirect()->route('participants.index');
    }


    public function update(Request $request, Participant_new $participant) {
        $participant->update($request->only('full_name','position','degree'));
        return redirect()->route('participants.index');
    }

    public function destroy(Participant_new $participant) {
        $participant->delete();
        return redirect()->route('participants.index');
    }
}
