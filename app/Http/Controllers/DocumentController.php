<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::where('object_type', 'baza')->withCount('documents')->get();
        $firstCategory = $categories->first();

        if ($firstCategory) {
            return $this->showByCategory($firstCategory->id, $request);
        }

        return view('admin.document.index', [
            'documents' => collect(),
            'category' => null,
            'categories' => $categories,
            'search' => null
        ]);
    }

    public function showByCategory($id, ?Request $request = null)
    {
        $categories = Category::where('object_type', 'baza')->withCount('documents')->get();
        $category = Category::withCount('documents')->find($id);

        if (!$category) {
            $category = $categories->first();
        }

        if (!$category) {
            return view('admin.document.index', [
                'documents' => collect(),
                'category' => null,
                'categories' => $categories,
                'search' => null
            ]);
        }

        $search = $request ? $request->input('search') : request('search');

        $query = $category->documents()->with('uploader');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $documents = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.document.index', compact('documents', 'category', 'categories', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip,ppt,pptx',
            'category_id' => 'required|exists:categories,id',
        ]);

        $filePath = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'name' => $request->name,
            'file_path' => $filePath,
            'uploaded_by' => Auth::id(),
        ]);

        $document->categories()->attach($request->category_id);

        return redirect()->back()->with('success', 'Ҳужжат муваффақиятли юкланди!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip,ppt,pptx',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($document->file_path);
            $filePath = $request->file('file')->store('documents', 'public');
            $document->file_path = $filePath;
        }

        $document->name = $request->name;
        $document->save();

        $document->categories()->sync([$request->category_id]);

        return redirect()->back()->with('success', 'Ҳужжат муваффақиятли янгиланди!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return redirect()->back()->with('success', 'Ҳужжат ўчирилди!');
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
