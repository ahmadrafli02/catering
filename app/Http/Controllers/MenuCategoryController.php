<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuCategoryController extends Controller
{
    public function index(): View
    {
        $categories = MenuCategory::latest()->paginate(15);
        return view('menu_categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('menu_categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:menu_categories,slug',
            'description' => 'nullable|string',
        ]);
        MenuCategory::create($data);
        return redirect()->route('menu-categories.index')->with('status', 'Category created');
    }

    public function edit(MenuCategory $menu_category): View
    {
        return view('menu_categories.edit', ['category' => $menu_category]);
    }

    public function update(Request $request, MenuCategory $menu_category): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:menu_categories,slug,' . $menu_category->id,
            'description' => 'nullable|string',
        ]);
        $menu_category->update($data);
        return redirect()->route('menu-categories.index')->with('status', 'Category updated');
    }

    public function destroy(MenuCategory $menu_category): RedirectResponse
    {
        $menu_category->delete();
        return back()->with('status', 'Category deleted');
    }
}
