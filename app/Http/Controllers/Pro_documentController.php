<?php

namespace App\Http\Controllers;

use App\Models\Pro_document;
use App\Models\Project;
use Illuminate\Http\Request;

class Pro_documentController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048'

        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('pro_documents', 'public');

            Pro_document::create([
                'project_id' => $project->id,
                'file_path' => $path,
            ]);
        }

        return back()->with('success', 'Hujjat yuklandi!');
    }
}
