<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index()
    {
        $libraries = Library::latest()->get();
        return view('admin.library.index', compact('libraries'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'author' => 'required',
            'pdf' => 'required|mimes:pdf'
        ]);

        $path = $request->file('pdf')->store('pdfs', 'public');

        Library::create([
            'name' => $request->name,
            'author' => $request->author,
            'pdf_path' => $path
        ]);

        return redirect()->route('library.index');
    }


    public function update(Request $request, Library $library)
    {
        $request->validate([
            'name' => 'required',
            'author' => 'required'
        ]);

        $library->update($request->only('name', 'author'));

        return redirect()->route('library.index');
    }

    public function destroy(Library $library)
    {
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

