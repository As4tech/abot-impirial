<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MenuCategory;
use App\Models\Room;
use App\Services\PosService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    public function __construct(private readonly PosService $pos)
    {
    }

    public function index(Request $request): View
    {
        $products = Product::orderBy('name')->get();
        $menuCategories = MenuCategory::with('items')->orderBy('name')->get();
        $cart = $this->pos->getCart();
        $total = $this->pos->total($cart);

        $rooms = Room::where('status', 'Available')->orderBy('room_number')->get();

        return view('pos', compact('products', 'menuCategories', 'cart', 'total', 'rooms'));
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $this->pos->addItem((int) $validated['product_id'], (int) ($validated['quantity'] ?? 1));

        return back()->with('status', 'Item added');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['nullable', 'string'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        if (!empty($validated['key'])) {
            $this->pos->updateLine($validated['key'], (int) $validated['quantity']);
        } elseif (!empty($validated['product_id'])) {
            $this->pos->updateItem((int) $validated['product_id'], (int) $validated['quantity']);
        }

        return back()->with('status', 'Cart updated');
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['nullable', 'string'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
        ]);

        if (!empty($validated['key'])) {
            $this->pos->removeLine($validated['key']);
        } elseif (!empty($validated['product_id'])) {
            $this->pos->removeItem((int) $validated['product_id']);
        }

        return back()->with('status', 'Item removed');
    }

    public function addMenu(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $this->pos->addMenuItem((int) $validated['menu_item_id'], (int) ($validated['quantity'] ?? 1));

        return back()->with('status', 'Item added');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_type' => ['required', 'in:walk-in,room'],
            'room_id' => ['nullable', 'integer', 'exists:rooms,id'],
        ]);

        // If room order, ensure the room is available
        $roomId = null;
        if ($validated['order_type'] === 'room') {
            $roomId = (int) ($validated['room_id'] ?? 0);
            $room = Room::where('id', $roomId)->where('status', 'Available')->first();
            abort_unless($room, 422, 'Selected room is not available');
        }

        $order = $this->pos->checkout($request->user(), $validated['order_type'], $roomId);

        return redirect()->route('pos.receipt', $order);
    }
}
