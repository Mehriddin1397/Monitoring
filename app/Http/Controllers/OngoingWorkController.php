<?php

namespace App\Http\Controllers;

use App\Models\OngoingWork;
use Illuminate\Http\Request;

class OngoingWorkController extends Controller
{
    public function index()
    {
        $works = OngoingWork::all();
        return view('admin.lab_jarayonda.index', compact('works'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'problems' => 'nullable|string',
            'process' => 'nullable|string',
            'process_color' => 'nullable|string',
            'remaining_time' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        OngoingWork::create($request->all());

        return redirect()->route('ongoing-works.index')->with('success', 'Jarayondagi ish qo‘shildi.');
    }

    public function show(OngoingWork $ongoingWork)
    {
        return view('ongoing-works.show', compact('ongoingWork'));
    }

    public function edit(OngoingWork $ongoingWork)
    {
        return view('ongoing-works.edit', compact('ongoingWork'));
    }

    public function update(Request $request, OngoingWork $ongoingWork)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'problems' => 'nullable|string',
            'process' => 'nullable|string',
            'process_color' => 'nullable|string',
            'remaining_time' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $ongoingWork->update($request->all());

        return redirect()->route('ongoing-works.index')->with('success', 'Jarayondagi ish yangilandi.');
    }

    public function destroy(OngoingWork $ongoingWork)
    {
        $ongoingWork->delete();
        return redirect()->route('ongoing-works.index')->with('success', 'Jarayondagi ish o‘chirildi.');
    }
}
