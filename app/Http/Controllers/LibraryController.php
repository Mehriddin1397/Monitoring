<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    public function index()
    {
        $category = Category::with('libraries')->findOrFail(11);

        $libraries = $category->libraries;
        return view('admin.library.index', compact('libraries', 'category'));
    }

    public function showByLib_Category($id)
    {
        $categories = Category::forObjectType('library');
        $category = Category::with('libraries')->findOrFail($id);
        $libraries = $category->libraries;

        return view('admin.library.index', compact('libraries','category','categories'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'author' => 'required',
            'pdf' => 'required|mimes:pdf'
        ]);

        $path = $request->file('pdf')->store('pdfs', 'public');

        $library = Library::create([
            'name' => $request->name,
            'author' => $request->author,
            'pdf_path' => $path
        ]);

        $library->categories()->attach($request->category_id);


        return redirect()->back()->with('success', 'Hujjat muvaffaqiyatli yuklandi!');
    }


    public function update(Request $request, Library $library)
    {
        $request->validate([
            'name' => 'required',
            'author' => 'required'
        ]);



        // Agar fayl bo'lsa, uni yangilaymiz
        if ($request->hasFile('file')) {
            // Eski faylni o‘chirish (ixtiyoriy)
            Storage::disk('public')->delete($library->pdf_path);

            // Yangi faylni saqlash
            $filePath = $request->file('file')->store('pdfs', 'public');
            $library->pdf_path = $filePath;
        }

        $library->update($request->only('name', 'author'));// Hujjat nomini yangilash


        // Kategoriya aloqasini yangilash
        $library->categories()->sync([$request->category_id]);

        return redirect()->back();
    }

    public function destroy(Library $library)
    {
        Storage::delete($library->pdf_path);

        $library->delete();
        return back();
    }

    // 🔍 REAL-TIME SEARCH
    public function search(Request $request)
    {
        $q = $request->q;

        $libraries = Library::where('name', 'LIKE', "%{$q}%")
            ->orWhere('author', 'LIKE', "%{$q}%")
            ->get();

        return response()->json($libraries);
    }

}

