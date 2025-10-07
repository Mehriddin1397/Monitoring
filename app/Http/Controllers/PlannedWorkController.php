<?php

namespace App\Http\Controllers;

use App\Models\PlannedWork;
use Illuminate\Http\Request;

class PlannedWorkController extends Controller
{
    public function index()
    {
        $works = PlannedWork::all();
        return view('admin.lab_rejalashtirilgan.index', compact('works'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'required_expenses' => 'nullable|string',
            'preparation_time' => 'nullable|string',
            'performance_results' => 'nullable|string',
            'required_amount' => 'nullable|string',
        ]);

        PlannedWork::create($request->all());

        return redirect()->route('planned-works.index')->with('success', 'Rejalashtirilgan ish qo‘shildi.');
    }


    public function update(Request $request, PlannedWork $plannedWork)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'required_expenses' => 'nullable|string',
            'preparation_time' => 'nullable|string',
            'performance_results' => 'nullable|string',
            'required_amount' => 'nullable|string',
        ]);

        $plannedWork->update($request->all());

        return redirect()->route('planned-works.index')->with('success', 'Rejalashtirilgan ish yangilandi.');
    }

    public function destroy(PlannedWork $plannedWork)
    {
        $plannedWork->delete();
        return redirect()->route('planned-works.index')->with('success', 'Rejalashtirilgan ish o‘chirildi.');
    }
}
