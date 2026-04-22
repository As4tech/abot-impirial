<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MenuCategory;
use App\Models\Room;
use App\Models\Register;
use App\Services\PosService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PosController extends Controller
{
    public function __construct(private readonly PosService $pos)
    {
    }

    public function index(Request $request)
    {
        // Require an open register before entering POS
        $userId = $request->user()->id;
        $openRegister = Register::where('user_id', $userId)
            ->where('status', 'open')
            ->orderByDesc('opened_at')
            ->first();
        if (! $openRegister) {
            return redirect()->route('pos.register.index');
        }

        $products = Product::orderBy('name')->get();
        $menuCategories = MenuCategory::with('items')->orderBy('name')->get();
        $cart = $this->pos->getCart();
        $total = $this->pos->total($cart);

        $rooms = Room::where('status', 'Available')->orderBy('room_number')->get();

        return view('pos', compact('products', 'menuCategories', 'cart', 'total', 'rooms', 'openRegister'));
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = (int) ($validated['quantity'] ?? 1);

        // Check stock availability
        if ($product->stock_quantity <= 0) {
            return back()
                ->with('error', "Product '{$product->name}' is out of stock and cannot be added to cart.")
                ->withInput();
        }

        if ($product->stock_quantity < $quantity) {
            return back()
                ->with('error', "Insufficient stock for '{$product->name}'. Available: {$product->stock_quantity}, Requested: {$quantity}")
                ->withInput();
        }

        $this->pos->addItem((int) $validated['product_id'], $quantity);

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
            $product = Product::findOrFail($validated['product_id']);
            $quantity = (int) $validated['quantity'];

            // Check stock availability when increasing quantity
            if ($quantity > 0 && $product->stock_quantity < $quantity) {
                return back()
                    ->with('error', "Insufficient stock for '{$product->name}'. Available: {$product->stock_quantity}, Requested: {$quantity}")
                    ->withInput();
            }

            $this->pos->updateItem((int) $validated['product_id'], $quantity);
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
            'room_rate_type' => ['nullable', 'in:long,short'],
            'room_rate_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        // If room order, ensure the room is available
        $roomId = null;
        if ($validated['order_type'] === 'room') {
            $roomId = (int) ($validated['room_id'] ?? 0);
            $room = Room::where('id', $roomId)->where('status', 'Available')->first();
            abort_unless($room, 422, 'Selected room is not available');
        }

        $rateType = $validated['room_rate_type'] ?? null;
        $ratePrice = isset($validated['room_rate_price']) && $validated['room_rate_price'] !== '' ? (float) $validated['room_rate_price'] : null;
        
        // Use custom price if provided, otherwise use default pricing
        if ($validated['order_type'] === 'room') {
            if ($ratePrice === null) {
                // Use default pricing from database
                $ratePrice = ($rateType === 'short')
                    ? (float) ($room->short_price ?? $room->price ?? 0)
                    : (float) ($room->long_price ?? $room->price ?? 0);
            }
            // If custom price is provided, use it as-is (staff has full discretion)
        }
        $order = $this->pos->checkout($request->user(), $validated['order_type'], $roomId, $rateType, $ratePrice);

        return redirect()->route('pos.payment.confirmation', $order)
            ->with('status', 'Order created successfully! Please record payment to complete the transaction.');
    }
}
