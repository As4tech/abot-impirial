<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\MenuItem;
use App\Events\OrderPlaced;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;

class PosService
{
    public function getCart(): array
    {
        return Session::get('pos_cart', []);
    }

    public function addItem(int $productId, int $quantity = 1): void
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('Quantity must be at least 1');
        }
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();
        $key = 'p:'.$product->id;
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'menu_item_id' => null,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
            ];
        }
        Session::put('pos_cart', $cart);
    }

    public function updateItem(int $productId, int $quantity): void
    {
        // Backward-compat for older calls using product_id
        $this->updateLine('p:'.$productId, $quantity);
    }

    public function removeItem(int $productId): void
    {
        // Backward-compat for older calls using product_id
        $this->removeLine('p:'.$productId);
    }

    public function addMenuItem(int $menuItemId, int $quantity = 1): void
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('Quantity must be at least 1');
        }
        $item = MenuItem::findOrFail($menuItemId);
        $cart = $this->getCart();
        $key = 'm:'.$item->id;
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => null,
                'menu_item_id' => $item->id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'quantity' => $quantity,
            ];
        }
        Session::put('pos_cart', $cart);
    }

    public function updateLine(string $key, int $quantity): void
    {
        $cart = $this->getCart();
        if (! isset($cart[$key])) {
            return;
        }
        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['quantity'] = $quantity;
        }
        Session::put('pos_cart', $cart);
    }

    public function removeLine(string $key): void
    {
        $cart = $this->getCart();
        unset($cart[$key]);
        Session::put('pos_cart', $cart);
    }

    public function total(array $cart = null): float
    {
        $cart = $cart ?? $this->getCart();
        $total = 0.0;
        foreach ($cart as $line) {
            $total += ((float) $line['price']) * ((int) $line['quantity']);
        }
        return round($total, 2);
    }

    public function checkout(Authenticatable $user, string $orderType = 'walk-in', ?int $roomId = null): Order
    {
        $cart = $this->getCart();
        if (empty($cart)) {
            throw new InvalidArgumentException('Cart is empty');
        }
        if (! in_array($orderType, ['walk-in', 'room'], true)) {
            throw new InvalidArgumentException('Invalid order type');
        }
        if ($orderType === 'room' && empty($roomId)) {
            throw new InvalidArgumentException('Room is required for room orders');
        }

        return DB::transaction(function () use ($user, $cart, $orderType, $roomId) {
            $order = Order::create([
                'user_id' => $user->getAuthIdentifier(),
                'order_type' => $orderType,
                'room_id' => $roomId,
                'total_amount' => $this->total($cart),
                'status' => 'Pending',
            ]);

            foreach ($cart as $line) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product_id'] ?? null,
                    'menu_item_id' => $line['menu_item_id'] ?? null,
                    'quantity' => (int) $line['quantity'],
                    'price' => (float) $line['price'],
                ]);
            }

            // Fire event for inventory deduction
            event(new OrderPlaced($order->id));

            Session::forget('pos_cart');
            return $order;
        });
    }
}
