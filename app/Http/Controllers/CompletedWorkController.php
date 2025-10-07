<?php

namespace App\Http\Controllers;

use App\Models\CompletedWork;
use Illuminate\Http\Request;

class CompletedWorkController extends Controller
{
    public function index()
    {
        $works = CompletedWork::all();
        return view('admin.lab_bajarilgan.index', compact('works'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'tech_info' => 'nullable|string',
            'results' => 'nullable|string',
        ]);

        CompletedWork::create($request->all());

        return redirect()->back();
    }

    public function update(Request $request, CompletedWork $completedWork)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'tech_info' => 'nullable|string',
            'results' => 'nullable|string',
        ]);

        $completedWork->update($request->all());

        return redirect()->route('completed-works.index')->with('success', 'Bajarilgan ish yangilandi.');
    }

    public function destroy(CompletedWork $completedWork)
    {
        $completedWork->delete();
        return redirect()->route('completed-works.index')->with('success', 'Bajarilgan ish o‘chirildi.');
    }
}
