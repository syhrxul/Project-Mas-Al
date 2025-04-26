<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index()
    {
        // Get items from cache or run the query if cache doesn't exist
        $items = Cache::remember('cached_items', now()->addMinutes(30), function () {
            return DB::table('items')->get();
        });
        
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            // Add other validation rules as needed
        ]);

        DB::table('items')->insert($validated);
        
        // Clear the cache when a new item is added
        Cache::forget('cached_items');
        
        return redirect()->route('items.index')->with('success', 'Item created successfully');
    }

    /**
     * Display the specified item.
     */
    public function show(string $id)
    {
        $item = DB::table('items')->find($id);
        
        if (!$item) {
            abort(404);
        }
        
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(string $id)
    {
        $item = DB::table('items')->find($id);
        
        if (!$item) {
            abort(404);
        }
        
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            // Add other validation rules as needed
        ]);

        DB::table('items')->where('id', $id)->update($validated);
        
        // Clear the cache when an item is updated
        Cache::forget('cached_items');
        
        return redirect()->route('items.index')->with('success', 'Item updated successfully');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(string $id)
    {
        DB::table('items')->where('id', $id)->delete();
        
        // Clear the cache when an item is deleted
        Cache::forget('cached_items');
        
        return redirect()->route('items.index')->with('success', 'Item deleted successfully');
    }
}