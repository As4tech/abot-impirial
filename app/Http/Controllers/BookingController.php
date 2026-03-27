<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $service)
    {
    }

    public function index(): View
    {
        $bookings = Booking::with(['room', 'guest'])->orderByDesc('id')->paginate(20);
        return view('bookings.index', compact('bookings'));
    }

    public function create(): View
    {
        $rooms = Room::where('status', 'Available')->orderBy('room_number')->get();
        return view('bookings.create', compact('rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:50'],
        ]);

        $booking = $this->service->checkIn((int) $data['room_id'], $data['guest_name'], $data['guest_phone'] ?? null);

        return redirect()->route('bookings.index')->with('status', 'Checked in booking #'.$booking->id);
    }

    public function checkout(Booking $booking): RedirectResponse
    {
        $this->service->checkOut($booking);
        return back()->with('status', 'Checked out booking #'.$booking->id);
    }
}
