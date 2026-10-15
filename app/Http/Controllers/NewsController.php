<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $newsList = News::orderBy('order')->latest()->get();
        return view('admin.brith.news', compact('newsList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'published_date' => 'nullable|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['published_date'] = $request->input('published_date', now()->format('Y-m-d'));

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('news', 'public');
            }
        }
        $validated['images'] = $imagePaths;

        News::create($validated);

        return redirect()->route('news.index')->with('success', 'Янгилик муваффақиятли қўшилди!');
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'published_date' => 'nullable|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'removed_images' => 'nullable|array',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $currentImages = $news->images ?? [];

        // O'chirilishi kerak bo'lgan rasmlarni o'chirish
        if ($request->has('removed_images')) {
            $removed = $request->input('removed_images');
            foreach ($removed as $imgPath) {
                Storage::disk('public')->delete($imgPath);
                $currentImages = array_values(array_filter($currentImages, fn($p) => $p !== $imgPath));
            }
        }

        // Yangi yuklangan rasmlarni qo'shish
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $currentImages[] = $file->store('news', 'public');
            }
        }

        $validated['images'] = $currentImages;

        $news->update($validated);

        return redirect()->route('news.index')->with('success', 'Янгилик муваффақиятли янгиланди!');
    }

    public function destroy(News $news)
    {
        if (is_array($news->images)) {
            foreach ($news->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $news->delete();

        return redirect()->route('news.index')->with('success', 'Янгилик ўчирилди!');
    }

    public function toggle(News $news)
    {
        $news->update(['is_active' => !$news->is_active]);
        return redirect()->route('news.index')->with('success', 'Янгилик ҳолати ўзгартирилди!');
    }
}
