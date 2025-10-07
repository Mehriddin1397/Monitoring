<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::with('user')->get();
        return view('admin.lab_taklif.index', compact('suggestions'));
    }

    public function create()
    {
        return view('suggestions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'suggestion' => 'required|string',
        ]);

        Suggestion::create([
            'user_id' => auth()->id(),
            'suggestion' => $request->suggestion,
        ]);

        return redirect()->route('suggestions.index')->with('success', 'Taklif qo‘shildi.');
    }

    public function show(Suggestion $suggestion)
    {
        return view('suggestions.show', compact('suggestion'));
    }

    public function edit(Suggestion $suggestion)
    {
        return view('suggestions.edit', compact('suggestion'));
    }

    public function update(Request $request, Suggestion $suggestion)
    {
        $request->validate([
            'suggestion' => 'required|string',
        ]);

        $suggestion->update([
            'suggestion' => $request->suggestion,
        ]);

        return redirect()->route('suggestions.index')->with('success', 'Taklif yangilandi.');
    }

    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();
        return redirect()->route('suggestions.index')->with('success', 'Taklif o‘chirildi.');
    }
}
