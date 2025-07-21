<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::all();

        return view('admin.category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'object_type' => 'required|string',
        ]);

        Category::create([
            'name' => $request->name,
            'object_type' => $request->object_type,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategoriya yaratildi!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'object_type' => 'required|string',
        ]);

        $category->update($request->only([
            'name',
            'object_type']));

        return redirect()->route('categories.index')->with('success', 'Kategoriya yaratildi!');
    }

    public function destroy(Request $request, Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategoriya yaratildi!');
    }


}
