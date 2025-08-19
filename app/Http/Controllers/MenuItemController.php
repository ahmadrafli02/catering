<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function index(): View
    {
        $items = MenuItem::with('category')->latest()->paginate(15);
        return view('menu_items.index', compact('items'));
    }

    public function create(): View
    {
        $categories = MenuCategory::orderBy('name')->get();
        return view('menu_items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'menu_category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        MenuItem::create($data);
        return redirect()->route('menu-items.index')->with('status', 'Item created');
    }

    public function edit(MenuItem $menu_item): View
    {
        $categories = MenuCategory::orderBy('name')->get();
        return view('menu_items.edit', ['item' => $menu_item, 'categories' => $categories]);
    }

    public function update(Request $request, MenuItem $menu_item): RedirectResponse
    {
        $data = $request->validate([
            'menu_category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $menu_item->update($data);
        return redirect()->route('menu-items.index')->with('status', 'Item updated');
    }

    public function destroy(MenuItem $menu_item): RedirectResponse
    {
        $menu_item->delete();
        return back()->with('status', 'Item deleted');
    }
}
