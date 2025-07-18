<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::with('uploader')->latest()->get();
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'file'     => 'required|mimes:pdf,doc,docx,ppt,pptx|max:10000',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents');

        Document::create([
            'name'        => $request->name,
            'file_path'   => $path,
            'category'    => $request->category,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Fayl muvaffaqiyatli yuklandi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        return Storage::download($document->file_path);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        Storage::delete($document->file_path);
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Fayl o‘chirildi');
    }
}
