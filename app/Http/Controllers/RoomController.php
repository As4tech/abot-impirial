<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $status = (string) $request->get('status', '');

        $roomsQuery = Room::query();
        if ($q !== '') {
            $roomsQuery->where(function ($w) use ($q) {
                $w->where('room_number', 'like', "%$q%")
                  ->orWhere('type', 'like', "%$q%");
            });
        }
        if (in_array($status, ['Available','Occupied','Cleaning'], true)) {
            $roomsQuery->where('status', $status);
        }

        $rooms = $roomsQuery->orderBy('room_number')->paginate(20)->withQueryString();

        return view('rooms.index', compact('rooms', 'q', 'status'));
    }

    public function create(): View
    {
        return view('rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'room_number' => ['required', 'string', 'max:50', 'unique:rooms,room_number'],
            'type' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:Available,Occupied,Cleaning'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
        if ($request->hasFile('image')) {
            $stored = $request->file('image')->store('uploads/rooms', 'public');
            $data['image_path'] = '/storage/' . $stored;
        }
        Room::create($data);
        return redirect()->route('rooms.index')->with('status', 'Room created');
    }

    public function edit(Room $room): View
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $data = $request->validate([
            'room_number' => ['required', 'string', 'max:50', 'unique:rooms,room_number,'.$room->id],
            'type' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:Available,Occupied,Cleaning'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
        if ($request->hasFile('image')) {
            $stored = $request->file('image')->store('uploads/rooms', 'public');
            $data['image_path'] = '/storage/' . $stored;
        }
        $room->update($data);
        return redirect()->route('rooms.index')->with('status', 'Room updated');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('status', 'Room deleted');
    }
}
