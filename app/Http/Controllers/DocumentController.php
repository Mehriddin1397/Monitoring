<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\Task;
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
        $category = Category::with('documents')->findOrFail(4);
        $documents = $category->documents;

        return view('', compact('documents','category'));
    }


    public function showByCategory($id)
    {
        $categories = Category::forObjectType('baza');
        $category = Category::with('documents')->findOrFail($id);
        $documents = $category->documents;

        return view('admin.document.index', compact('documents','category','categories'));
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
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Faylni saqlash
        $filePath = $request->file('file')->store('documents', 'public');

        // Hujjatni yaratish
        $document = Document::create([
            'name' => $request->name,
            'file_path' => $filePath,
            'uploaded_by' => Auth::id(),
        ]);

        // Category bilan bog'lash
        $document->categories()->attach($request->category_id);

        return redirect()->back()->with('success', 'Hujjat muvaffaqiyatli yuklandi!');
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
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Agar fayl bo'lsa, uni yangilaymiz
        if ($request->hasFile('file')) {
            // Eski faylni o‘chirish (ixtiyoriy)
            Storage::disk('public')->delete($document->file_path);

            // Yangi faylni saqlash
            $filePath = $request->file('file')->store('documents', 'public');
            $document->file_path = $filePath;
        }

        // Hujjat nomini yangilash
        $document->name = $request->name;
        $document->save();

        // Kategoriya aloqasini yangilash
        $document->categories()->sync([$request->category_id]);

        return redirect()->back()->with('success', 'Hujjat muvaffaqiyatli yangilandi!');
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


    public function showFile($id)
    {
        $document = Document::findOrFail($id);
        $categories = $document->categories;

        if ($document->file_path) {
            $filePath = $document->file_path;

        } else {
            abort(404, 'Fayl topilmadi');
        }



        return view('admin.document.pdf', compact('categories', 'filePath'));
    }
}
