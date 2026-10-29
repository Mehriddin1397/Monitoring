<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('object_type', 'library')->withCount('libraries')->get();
        $firstCategory = $categories->first();

        if ($firstCategory) {
            return $this->showByLib_Category($firstCategory->id, $request);
        }

        return view('admin.library.index', [
            'libraries' => collect(),
            'category' => null,
            'categories' => $categories,
            'search' => null
        ]);
    }

    public function showByLib_Category($id, ?Request $request = null)
    {
        $categories = Category::where('object_type', 'library')->withCount('libraries')->get();
        $category = Category::withCount('libraries')->find($id);

        if (!$category) {
            $category = $categories->first();
        }

        if (!$category) {
            return view('admin.library.index', [
                'libraries' => collect(),
                'category' => null,
                'categories' => $categories,
                'search' => null
            ]);
        }

        $search = $request ? $request->input('search') : request('search');

        $query = $category->libraries();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%");
            });
        }

        $libraries = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.library.index', compact('libraries', 'category', 'categories', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'pdf' => 'required|file|mimes:pdf',
            'category_id' => 'required|exists:categories,id',
        ]);

        $path = $request->file('pdf')->store('pdfs', 'public');

        $library = Library::create([
            'name' => $request->name,
            'author' => $request->author,
            'pdf_path' => $path
        ]);

        $library->categories()->attach($request->category_id);

        return redirect()->back()->with('success', 'Адабиёт муваффақиятли юкланди!');
    }

    public function update(Request $request, Library $library)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|file|mimes:pdf',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($library->pdf_path);
            $filePath = $request->file('file')->store('pdfs', 'public');
            $library->pdf_path = $filePath;
        }

        $library->name = $request->name;
        $library->author = $request->author;
        $library->save();

        $library->categories()->sync([$request->category_id]);

        return redirect()->back()->with('success', 'Адабиёт муваффақиятли янгиланди!');
    }

    public function destroy(Library $library)
    {
        Storage::disk('public')->delete($library->pdf_path);
        $library->delete();
        return redirect()->back()->with('success', 'Адабиёт ўчирилди!');
    }

    // REAL-TIME SEARCH (JSON)
    public function search(Request $request)
    {
        $q = $request->q;

        $libraries = Library::where('name', 'LIKE', "%{$q}%")
            ->orWhere('author', 'LIKE', "%{$q}%")
            ->latest('id')
            ->limit(20)
            ->get();

        return response()->json($libraries);
    }
}
