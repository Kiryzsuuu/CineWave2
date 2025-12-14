<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        ActivityLog::logActivity(auth()->id(), 'category_created', "Created category: {$category->name}", ['category_id' => $category->id]);

        return redirect()->route('admin.categories.index')->with('success', 'Category berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        ActivityLog::logActivity(auth()->id(), 'category_updated', "Updated category: {$category->name}", ['category_id' => $category->id]);

        return redirect()->route('admin.categories.index')->with('success', 'Category berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        
        ActivityLog::logActivity(auth()->id(), 'category_deleted', "Deleted category: {$category->name}", ['category_id' => $category->id]);

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category berhasil dihapus!');
    }
}
