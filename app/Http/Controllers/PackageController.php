<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::latest()->paginate(15);
        return view('packages.index', compact('packages'));
    }

    public function create(): View
    {
        $items = MenuItem::active()->orderBy('name')->get();
        return view('packages.create', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $package = Package::create($data);
        return redirect()->route('packages.edit', $package)->with('status', 'Package created');
    }

    public function edit(Package $package): View
    {
        $items = MenuItem::orderBy('name')->get();
        $package->load('items');
        return view('packages.edit', compact('package', 'items'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $package->update($data);
        return back()->with('status', 'Package updated');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();
        return back()->with('status', 'Package deleted');
    }
}
