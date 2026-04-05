<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\MenuItem;
use App\Models\Recipe;
use App\Models\KitchenStockMovement;
use App\Models\Room;
use App\Models\Booking;
use App\Events\OrderPlaced;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Auth\Authenticatable;
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

    public function checkout(Authenticatable $user, string $orderType = 'walk-in', ?int $roomId = null, ?string $roomRateType = null, ?float $roomRatePrice = null): Order
    {
        $cart = $this->getCart();
        // Allow empty cart for room check-ins; otherwise require at least one item.
        if (empty($cart) && $orderType !== 'room') {
            throw new InvalidArgumentException('Cart is empty');
        }
        if (! in_array($orderType, ['walk-in', 'room'], true)) {
            throw new InvalidArgumentException('Invalid order type');
        }
        if ($orderType === 'room' && empty($roomId)) {
            throw new InvalidArgumentException('Room is required for room orders');
        }

        return DB::transaction(function () use ($user, $cart, $orderType, $roomId, $roomRateType, $roomRatePrice) {
            $baseTotal = $this->total($cart);
            $roomCharge = 0.0;
            if ($orderType === 'room' && is_numeric($roomRatePrice) && (float) $roomRatePrice > 0) {
                $roomCharge = round((float) $roomRatePrice, 2);
            }

            // Pre-validate stock availability for menu items
            $stockIssues = $this->validateStockAvailability($cart);
            if (!empty($stockIssues)) {
                throw new InvalidArgumentException('Insufficient stock for: ' . implode(', ', $stockIssues));
            }

            $order = Order::create([
                'user_id' => $user->getAuthIdentifier(),
                'order_type' => $orderType,
                'room_id' => $roomId,
                'total_amount' => round($baseTotal + $roomCharge, 2),
                'status' => 'Pending',
            ]);

            // Create order items and deduct stock atomically
            foreach ($cart as $line) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product_id'] ?? null,
                    'menu_item_id' => $line['menu_item_id'] ?? null,
                    'quantity' => (int) $line['quantity'],
                    'price' => (float) $line['price'],
                ]);

                // Deduct kitchen stock for menu items with proper locking
                if (!empty($line['menu_item_id'])) {
                    $this->deductStockForMenuItem($line['menu_item_id'], (int) $line['quantity'], $order, $user->getAuthIdentifier());
                }
            }

            // Add room charge as its own line (no product/menu item)
            if ($roomCharge > 0) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => null,
                    'menu_item_id' => null,
                    'quantity' => 1,
                    'price' => $roomCharge,
                ]);
            }

            // Mark room as occupied when checking in
            if ($orderType === 'room' && $roomId) {
                Room::where('id', $roomId)->update(['status' => 'Occupied']);
                // create booking record
                Booking::create([
                    'order_id' => $order->id,
                    'room_id' => $roomId,
                    'rate_type' => $roomRateType,
                    'hourly_rate' => $roomRateType === 'short' ? (float) ($roomRatePrice ?? 0) : null,
                    'nightly_rate' => $roomRateType === 'long' ? (float) ($roomRatePrice ?? 0) : null,
                    'initial_charge' => (float) ($roomRatePrice ?? 0),
                    'check_in_at' => now(),
                    'status' => 'active',
                ]);
            }

            // Fire event for inventory deduction
            event(new OrderPlaced($order->id));

            Session::forget('pos_cart');
            return $order;
        });
    }

    /**
     * Validate stock availability for all menu items in cart
     */
    private function validateStockAvailability(array $cart): array
    {
        $stockIssues = [];
        
        foreach ($cart as $line) {
            if (empty($line['menu_item_id'])) continue;
            
            $recipes = Recipe::with('ingredient')->where('menu_item_id', $line['menu_item_id'])->get();
            
            foreach ($recipes as $recipe) {
                $requiredQuantity = $recipe->quantity_required * (int) $line['quantity'];
                $availableStock = $recipe->ingredient->current_stock;
                
                if ($availableStock < $requiredQuantity) {
                    $stockIssues[] = "{$recipe->ingredient->name} (need {$requiredQuantity}, have {$availableStock})";
                }
            }
        }
        
        return $stockIssues;
    }

    /**
     * Atomically deduct stock for a menu item with proper locking
     */
    private function deductStockForMenuItem(int $menuItemId, int $quantity, $order, int $userId): void
    {
        // Get recipes with locked ingredients to prevent race conditions
        $recipes = Recipe::where('menu_item_id', $menuItemId)
            ->with(['ingredient' => function ($query) {
                $query->lockForUpdate();
            }])
            ->get();
        
        foreach ($recipes as $recipe) {
            $requiredQuantity = $recipe->quantity_required * $quantity;
            
            // Double-check stock availability within transaction
            if ($recipe->ingredient->current_stock < $requiredQuantity) {
                throw new InvalidArgumentException(
                    "Insufficient stock for {$recipe->ingredient->name}. Available: {$recipe->ingredient->current_stock}, Required: {$requiredQuantity}"
                );
            }
            
            // Create stock movement with proper reference
            KitchenStockMovement::create([
                'kitchen_ingredient_id' => $recipe->ingredient->id,
                'type' => 'usage',
                'quantity' => -$requiredQuantity,
                'unit_cost' => $recipe->ingredient->cost_per_unit,
                'reference_type' => get_class($order),
                'reference_id' => $order->id,
                'notes' => "Used for {$quantity}x menu item",
                'user_id' => $userId,
            ]);
            
            // Update stock atomically
            $recipe->ingredient->decrement('current_stock', $requiredQuantity);
        }
    }
}
