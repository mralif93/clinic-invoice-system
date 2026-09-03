<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display listing of item master records with category filter.
     */
    public function index(Request $request)
    {
        $query = Item::query()->orderBy('category')->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $perPage = (int) $request->input('per_page', 12);
        $items = $query->paginate($perPage)->withQueryString();

        $categories = ['Consultation', 'Medication', 'Procedure', 'Lab'];

        $stats = [
            'total_items' => Item::count(),
            'medications' => Item::where('category', 'Medication')->count(),
            'procedures' => Item::where('category', 'Procedure')->count(),
            'low_stock' => Item::where('category', 'Medication')->where('stock_quantity', '<', 50)->count(),
        ];

        return view('admin.items.index', compact('items', 'categories', 'stats'));
    }

    /**
     * Show form to create a new item.
     */
    public function create()
    {
        $categories = ['Consultation', 'Medication', 'Procedure', 'Lab'];
        return view('admin.items.create', compact('categories'));
    }

    /**
     * Store newly created item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:items,code'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        $item = Item::create($validated);

        return redirect()->route('admin.items.index')
            ->with('success', "Item '{$item->name}' ({$item->code}) created successfully.");
    }

    /**
     * Show form to edit item.
     */
    public function edit(Item $item)
    {
        $categories = ['Consultation', 'Medication', 'Procedure', 'Lab'];
        return view('admin.items.edit', compact('item', 'categories'));
    }

    /**
     * Update item.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:items,code,' . $item->id],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        $item->update($validated);

        return redirect()->route('admin.items.index')
            ->with('success', "Item '{$item->name}' updated successfully.");
    }

    /**
     * Delete item.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.items.index')
            ->with('success', "Item '{$item->name}' removed from catalog.");
    }
}
