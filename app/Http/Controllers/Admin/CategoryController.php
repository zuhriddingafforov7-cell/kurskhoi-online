<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('courses')->orderBy('title')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255', 'unique:categories,title'],
            'description' => ['nullable', 'string'],
        ]);

        $data['slug'] = Str::slug($data['title']);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Категория илова шуд!');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255', 'unique:categories,title,' . $category->id],
            'description' => ['nullable', 'string'],
        ]);

        $data['slug'] = Str::slug($data['title']);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Категория навсозӣ шуд!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->courses()->exists()) {
            return back()->with('error', 'Категория дорои курсҳо мебошад ва нест карда намешавад.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Категория нест карда шуд.');
    }
}
