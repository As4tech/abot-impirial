<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $status = (string) $request->get('status', '');
        $stayType = (string) $request->get('stay_type', '');

        $roomsQuery = Room::query()->with('roomType');
        if ($q !== '') {
            $roomsQuery->where(function ($w) use ($q) {
                $w->where('room_number', 'like', "%$q%")
                  ->orWhereHas('roomType', function ($roomTypes) use ($q) {
                      $roomTypes->where('name', 'like', "%$q%");
                  });
            });
        }
        if (in_array($status, ['Available','Occupied','Cleaning'], true)) {
            $roomsQuery->where('status', $status);
        }
        if (in_array($stayType, ['short', 'long'], true)) {
            $roomsQuery->where('stay_type', $stayType);
        }

        $rooms = $roomsQuery->orderBy('room_number')->paginate(20)->withQueryString();

        return view('rooms.index', compact('rooms', 'q', 'status', 'stayType'));
    }

    public function create(): View
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();

        return view('rooms.create', compact('roomTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'room_number' => ['required', 'string', 'max:50', 'unique:rooms,room_number'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'stay_type' => ['required', 'in:short,long'],
            'long_price' => ['required', 'numeric', 'min:0'],
            'short_price' => ['required', 'numeric', 'min:0'],
            'has_ac' => ['nullable', 'boolean'],
            'has_fan' => ['nullable', 'boolean'],
            'has_tv' => ['nullable', 'boolean'],
            'has_fridge' => ['nullable', 'boolean'],
            'bed_type' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:Available,Occupied,Cleaning'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
        $data['has_ac'] = $request->boolean('has_ac');
        $data['has_fan'] = $request->boolean('has_fan');
        $data['has_tv'] = $request->boolean('has_tv');
        $data['has_fridge'] = $request->boolean('has_fridge');
        $roomType = RoomType::findOrFail($data['room_type_id']);
        $data['type'] = $roomType->name;
        $data['price'] = $data['long_price'];
        if ($request->hasFile('image')) {
            $stored = $request->file('image')->store('uploads/rooms', 'public');
            $data['image_path'] = '/storage/' . $stored;
        }
        Room::create($data);
        return redirect()->route('rooms.index')->with('status', 'Room created');
    }

    public function edit(Room $room): View
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();

        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $data = $request->validate([
            'room_number' => ['required', 'string', 'max:50', 'unique:rooms,room_number,'.$room->id],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'stay_type' => ['required', 'in:short,long'],
            'long_price' => ['required', 'numeric', 'min:0'],
            'short_price' => ['required', 'numeric', 'min:0'],
            'has_ac' => ['nullable', 'boolean'],
            'has_fan' => ['nullable', 'boolean'],
            'has_tv' => ['nullable', 'boolean'],
            'has_fridge' => ['nullable', 'boolean'],
            'bed_type' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:Available,Occupied,Cleaning'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
        $data['has_ac'] = $request->boolean('has_ac');
        $data['has_fan'] = $request->boolean('has_fan');
        $data['has_tv'] = $request->boolean('has_tv');
        $data['has_fridge'] = $request->boolean('has_fridge');
        $roomType = RoomType::findOrFail($data['room_type_id']);
        $data['type'] = $roomType->name;
        $data['price'] = $data['long_price'];
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
