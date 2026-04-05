<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoomTypeController extends Controller
{
    public function index(): View
    {
        $roomTypes = RoomType::withCount('rooms')->get();
        return view('room-types.index', compact('roomTypes'));
    }

    public function create(): View
    {
        return view('room-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_types',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:255',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $validated['amenities'] = $validated['amenities'] ?? [];
        $validated['is_active'] = $request->boolean('is_active', true);

        RoomType::create($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Room type created successfully.');
    }

    public function show(RoomType $roomType): View
    {
        $roomType->load('rooms');
        return view('room-types.show', compact('roomType'));
    }

    public function edit(RoomType $roomType): View
    {
        return view('room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_types,name,'.$roomType->id,
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:255',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $validated['amenities'] = $validated['amenities'] ?? [];
        $validated['is_active'] = $request->boolean('is_active', $roomType->is_active);

        $roomType->update($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Room type updated successfully.');
    }

    public function destroy(RoomType $roomType): RedirectResponse
    {
        if ($roomType->rooms()->exists()) {
            return redirect()->route('room-types.index')
                ->with('error', 'Cannot delete room type with associated rooms.');
        }

        $roomType->delete();

        return redirect()->route('room-types.index')
            ->with('success', 'Room type deleted successfully.');
    }
}
