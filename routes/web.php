<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductCategoryController as InventoryCategoryController;
use App\Http\Controllers\ProductController as InventoryProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\Admin\RoleAdminController;
use App\Http\Controllers\Admin\PermissionAdminController;
use App\Http\Controllers\Admin\UserRoleAdminController;
use App\Http\Controllers\Admin\PermissionMatrixController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\KitchenStockController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\KitchenPurchaseController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // POS - permission based
    Route::get('/pos', [PosController::class, 'index'])->middleware('permission:pos.view')->name('pos.index');
    Route::post('/pos/cart/add', [PosController::class, 'add'])->middleware('permission:pos.view')->name('pos.cart.add');
    Route::post('/pos/cart/add-menu', [PosController::class, 'addMenu'])->middleware('permission:pos.view')->name('pos.cart.addMenu');
    Route::post('/pos/cart/update', [PosController::class, 'update'])->middleware('permission:pos.view')->name('pos.cart.update');
    Route::post('/pos/cart/remove', [PosController::class, 'remove'])->middleware('permission:pos.view')->name('pos.cart.remove');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->middleware('permission:pos.view')->name('pos.checkout');
    Route::post('/pos/payments', [PaymentController::class, 'store'])->middleware('permission:pos.view')->name('pos.payments.store');
    Route::get('/pos/payment/confirmation/{order}', [PaymentController::class, 'paymentConfirmation'])->middleware('permission:pos.view')->name('pos.payment.confirmation');
    Route::get('/pos/receipt/{order}', [PaymentController::class, 'receipt'])->middleware('permission:pos.view')->name('pos.receipt');
    Route::get('/pos/receipt/{order}/thermal', [PaymentController::class, 'receiptThermal'])->middleware('permission:pos.view')->name('pos.receipt.thermal');
    // POS Register
    Route::get('/pos/register', [\App\Http\Controllers\RegisterController::class, 'index'])
        ->middleware('permission:registers.view|registers.open|registers.close')
        ->name('pos.register.index');
    Route::post('/pos/register/open', [\App\Http\Controllers\RegisterController::class, 'open'])
        ->middleware('permission:registers.open')
        ->name('pos.register.open');
    Route::post('/pos/register/close', [\App\Http\Controllers\RegisterController::class, 'close'])
        ->middleware('permission:registers.close')
        ->name('pos.register.close');

    // Rooms (permission based) - requires rooms.* permissions
    Route::get('rooms', [RoomController::class, 'index'])->middleware('permission:rooms.view')->name('rooms.index');
    Route::get('rooms/create', [RoomController::class, 'create'])->middleware('permission:rooms.create')->name('rooms.create');
    Route::post('rooms', [RoomController::class, 'store'])->middleware('permission:rooms.create')->name('rooms.store');
    Route::get('rooms/{room}/edit', [RoomController::class, 'edit'])->middleware('permission:rooms.update')->name('rooms.edit');
    Route::put('rooms/{room}', [RoomController::class, 'update'])->middleware('permission:rooms.update')->name('rooms.update');
    Route::delete('rooms/{room}', [RoomController::class, 'destroy'])->middleware('permission:rooms.delete')->name('rooms.destroy');

    // Room Types - permission based
    Route::get('room-types', [RoomTypeController::class, 'index'])->middleware('permission:room-types.view')->name('room-types.index');
    Route::get('room-types/create', [RoomTypeController::class, 'create'])->middleware('permission:room-types.create')->name('room-types.create');
    Route::post('room-types', [RoomTypeController::class, 'store'])->middleware('permission:room-types.create')->name('room-types.store');
    Route::get('room-types/{room_type}', [RoomTypeController::class, 'show'])->middleware('permission:room-types.view')->name('room-types.show');
    Route::get('room-types/{room_type}/edit', [RoomTypeController::class, 'edit'])->middleware('permission:room-types.edit')->name('room-types.edit');
    Route::put('room-types/{room_type}', [RoomTypeController::class, 'update'])->middleware('permission:room-types.edit')->name('room-types.update');
    Route::delete('room-types/{room_type}', [RoomTypeController::class, 'destroy'])->middleware('permission:room-types.delete')->name('room-types.destroy');

    // Bookings (check-in / check-out) - permission based
    Route::get('bookings', [BookingController::class, 'index'])->middleware('permission:bookings.view')->name('bookings.index');
    Route::get('bookings/create', [BookingController::class, 'create'])->middleware('permission:bookings.create')->name('bookings.create');
    Route::post('bookings', [BookingController::class, 'store'])->middleware('permission:bookings.create')->name('bookings.store');
    Route::post('bookings/{booking}/checkout', [BookingController::class, 'checkout'])->middleware('permission:bookings.update')->name('bookings.checkout');

    // Restaurant CRUD: Menu Categories and Items - permission based
    Route::resource('menu-categories', MenuCategoryController::class)->except(['show'])->middleware('permission:menu_items.update');
    // Menu Items (permission based)
    Route::get('menu-items', [MenuItemController::class, 'index'])->middleware('permission:menu_items.view')->name('menu-items.index');
    Route::get('menu-items/create', [MenuItemController::class, 'create'])->middleware('permission:menu_items.create')->name('menu-items.create');
    Route::post('menu-items', [MenuItemController::class, 'store'])->middleware('permission:menu_items.create')->name('menu-items.store');
    Route::get('menu-items/{menu_item}/edit', [MenuItemController::class, 'edit'])->middleware('permission:menu_items.update')->name('menu-items.edit');
    Route::put('menu-items/{menu_item}', [MenuItemController::class, 'update'])->middleware('permission:menu_items.update')->name('menu-items.update');
    Route::delete('menu-items/{menu_item}', [MenuItemController::class, 'destroy'])->middleware('permission:menu_items.delete')->name('menu-items.destroy');

    // Kitchen (KOT) - permission based
    Route::get('/kitchen', [KitchenController::class, 'index'])->middleware('permission:view_kitchen')->name('kitchen.index');
    Route::post('/kitchen/orders/{order}/status', [KitchenController::class, 'updateStatus'])->middleware('permission:view_kitchen')->name('kitchen.orders.status');
    Route::post('/kitchen/orders/bulk-update', [KitchenController::class, 'bulkUpdateStatus'])->middleware('permission:view_kitchen')->name('kitchen.orders.bulk-update');

    // Restaurant - permission based
    Route::get('/restaurant', fn () => view('restaurant'))->middleware('permission:orders.view')->name('restaurant.index');

    // Inventory - permission based
    Route::prefix('inventory')->as('inventory.')->group(function () {
        Route::get('/', fn () => redirect()->route('inventory.products.index'))->name('index');

        // Products: granular permissions
        Route::get('products', [InventoryProductController::class, 'index'])
            ->name('products.index')
            ->middleware('permission:products.view|inventory.view');
        Route::get('products/create', [InventoryProductController::class, 'create'])
            ->name('products.create')
            ->middleware('can:products.create');
        Route::post('products', [InventoryProductController::class, 'store'])
            ->name('products.store')
            ->middleware('can:products.create');
        Route::get('products/{product}/edit', [InventoryProductController::class, 'edit'])
            ->name('products.edit')
            ->middleware('can:products.update');
        Route::put('products/{product}', [InventoryProductController::class, 'update'])
            ->name('products.update')
            ->middleware('can:products.update');
        Route::delete('products/{product}', [InventoryProductController::class, 'destroy'])
            ->name('products.destroy')
            ->middleware('can:products.delete');

        // Categories
        Route::get('categories', [InventoryCategoryController::class, 'index'])
            ->name('categories.index')
            ->middleware('can:inventory.view');
        Route::get('categories/create', [InventoryCategoryController::class, 'create'])
            ->name('categories.create')
            ->middleware('can:inventory.manage');
        Route::post('categories', [InventoryCategoryController::class, 'store'])
            ->name('categories.store')
            ->middleware('can:inventory.manage');
        Route::get('categories/{product_category}/edit', [InventoryCategoryController::class, 'edit'])
            ->name('categories.edit')
            ->middleware('can:inventory.manage');
        Route::put('categories/{product_category}', [InventoryCategoryController::class, 'update'])
            ->name('categories.update')
            ->middleware('can:inventory.manage');
        Route::delete('categories/{product_category}', [InventoryCategoryController::class, 'destroy'])
            ->name('categories.destroy')
            ->middleware('can:inventory.manage');

        // Stock Movements (read-only)
        Route::get('movements', [StockMovementController::class, 'index'])
            ->name('movements.index')
            ->middleware('can:inventory.view');

        // Purchase Management (optional: manage permission)
        Route::resource('suppliers', App\Http\Controllers\SupplierController::class)
            ->except(['show'])
            ->middleware('can:inventory.manage');
        Route::resource('purchases', App\Http\Controllers\PurchaseController::class)
            ->only(['index','create','store','show'])
            ->middleware('can:inventory.manage');
    });

    // Reports - permission based (granular)
    Route::get('/reports', [ReportsController::class, 'index'])
        ->middleware('permission:reports.sales.view|reports.inventory.view|reports.bookings.view|reports.registers.view')
        ->name('reports.index');
    Route::get('/reports/daily-sales', [ReportsController::class, 'dailySales'])
        ->middleware('permission:reports.sales.view')
        ->name('reports.daily');
    Route::get('/reports/inventory-movement', [ReportsController::class, 'inventoryMovement'])
        ->middleware('permission:reports.inventory.view')
        ->name('reports.inventory');
    Route::get('/reports/bookings', [ReportsController::class, 'bookings'])
        ->middleware('permission:reports.bookings.view')
        ->name('reports.bookings');
    Route::get('/reports/menu-items', [ReportsController::class, 'menuItemsSales'])
        ->middleware('permission:reports.sales.view')
        ->name('reports.menu_items');
    Route::get('/reports/products', [ReportsController::class, 'productSales'])
        ->middleware('permission:reports.sales.view')
        ->name('reports.products');
    Route::get('/reports/products-profit', [ReportsController::class, 'productProfit'])
        ->middleware('permission:reports.sales.view')
        ->name('reports.products_profit');
    Route::get('/reports/registers', [ReportsController::class, 'registers'])
        ->middleware('permission:reports.registers.view')
        ->name('reports.registers');

    // Expenses
    Route::middleware('can:expenses.view')->group(function () {
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
        Route::get('/expenses/categories', [ExpenseCategoryController::class, 'index'])->name('expenses.categories.index');
    });
    Route::middleware('can:expenses.manage')->group(function () {
        Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

        Route::get('/expenses/categories/create', [ExpenseCategoryController::class, 'create'])->name('expenses.categories.create');
        Route::post('/expenses/categories', [ExpenseCategoryController::class, 'store'])->name('expenses.categories.store');
        Route::get('/expenses/categories/{category}/edit', [ExpenseCategoryController::class, 'edit'])->name('expenses.categories.edit');
        Route::put('/expenses/categories/{category}', [ExpenseCategoryController::class, 'update'])->name('expenses.categories.update');
        Route::delete('/expenses/categories/{category}', [ExpenseCategoryController::class, 'destroy'])->name('expenses.categories.destroy');
    });

    // Kitchen Stock Management
    Route::prefix('kitchen-stock')->as('kitchen-stock.')->group(function () {
        Route::get('/', [KitchenStockController::class, 'index'])->name('index');
        Route::get('/create', [KitchenStockController::class, 'create'])->name('create');
        Route::post('/', [KitchenStockController::class, 'store'])->name('store');
        Route::get('/{kitchenStock}', [KitchenStockController::class, 'show'])->name('show');
        Route::get('/{kitchenStock}/edit', [KitchenStockController::class, 'edit'])->name('edit');
        Route::put('/{kitchenStock}', [KitchenStockController::class, 'update'])->name('update');
        Route::delete('/{kitchenStock}', [KitchenStockController::class, 'destroy'])->name('destroy');
        Route::post('/{kitchenStock}/adjust', [KitchenStockController::class, 'adjustStock'])->name('adjust');
        Route::get('/low-stock', [KitchenStockController::class, 'lowStock'])->name('low-stock');
    });

    // Recipe Management
    Route::prefix('recipes')->as('recipes.')->group(function () {
        Route::get('/', [RecipeController::class, 'index'])->name('index');
        Route::get('/create', [RecipeController::class, 'create'])->name('create');
        Route::post('/', [RecipeController::class, 'store'])->name('store');
        Route::get('/{recipe}', [RecipeController::class, 'show'])->name('show');
        Route::get('/{recipe}/edit', [RecipeController::class, 'edit'])->name('edit');
        Route::put('/{recipe}', [RecipeController::class, 'update'])->name('update');
        Route::delete('/{recipe}', [RecipeController::class, 'destroy'])->name('destroy');
        Route::get('/menu-item/{menuItem}', [RecipeController::class, 'forMenuItem'])->name('for-menu-item');
        Route::post('/menu-item/{menuItem}', [RecipeController::class, 'addIngredient'])->name('add-ingredient');
    });

    // Kitchen Purchase Management
    Route::prefix('kitchen-purchases')->as('kitchen-purchases.')->group(function () {
        Route::get('/', [KitchenPurchaseController::class, 'index'])->name('index');
        Route::get('/create', [KitchenPurchaseController::class, 'create'])->name('create');
        Route::post('/', [KitchenPurchaseController::class, 'store'])->name('store');
        Route::get('/{purchase}', [KitchenPurchaseController::class, 'show'])->name('show');
        Route::get('/low-stock', [KitchenPurchaseController::class, 'lowStock'])->name('low-stock');
    });

    // RBAC Admin - Roles, Permissions, User roles
    Route::middleware('can:manage-roles')->group(function () {
        Route::get('/admin/roles', [RoleAdminController::class, 'index'])->name('admin.roles.index');
        Route::get('/admin/roles/create', [RoleAdminController::class, 'create'])->name('admin.roles.create');
        Route::post('/admin/roles', [RoleAdminController::class, 'store'])->name('admin.roles.store');
        Route::get('/admin/roles/{role}/edit', [RoleAdminController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/admin/roles/{role}', [RoleAdminController::class, 'update'])->name('admin.roles.update');
        Route::delete('/admin/roles/{role}', [RoleAdminController::class, 'destroy'])->name('admin.roles.destroy');
    });

    Route::middleware('can:manage-permissions')->group(function () {
        // Redirect the old permissions index to the new Permission Matrix
        Route::get('/admin/permissions', [PermissionMatrixController::class, 'index'])->name('admin.permissions.index');
        Route::post('/admin/permissions', [PermissionAdminController::class, 'store'])->name('admin.permissions.store');
        Route::delete('/admin/permissions/{permission}', [PermissionAdminController::class, 'destroy'])->name('admin.permissions.destroy');
        // Permission Matrix
        Route::get('/admin/permissions/matrix', [PermissionMatrixController::class, 'index'])->name('admin.permissions.matrix');
        Route::post('/admin/permissions/matrix', [PermissionMatrixController::class, 'update'])->name('admin.permissions.matrix.update');
    });

    Route::middleware('can:manage-users')->group(function () {
        Route::get('/admin/users/roles', [UserRoleAdminController::class, 'index'])->name('admin.users.roles.index');
        Route::put('/admin/users/{user}/roles', [UserRoleAdminController::class, 'update'])->name('admin.users.roles.update');
        Route::get('/admin/users/create', [\App\Http\Controllers\Admin\UserAdminController::class, 'create'])->name('admin.users.create');
        Route::get('/admin/users/{user}/edit', [\App\Http\Controllers\Admin\UserAdminController::class, 'edit'])->name('admin.users.edit');
        Route::post('/admin/users', [\App\Http\Controllers\Admin\UserAdminController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{user}', [\App\Http\Controllers\Admin\UserAdminController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [\App\Http\Controllers\Admin\UserAdminController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/users/{user}', [\App\Http\Controllers\Admin\UserAdminController::class, 'show'])->name('admin.users.show');
    });

    // Settings - Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';
