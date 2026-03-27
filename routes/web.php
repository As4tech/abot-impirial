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
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\Admin\RoleAdminController;
use App\Http\Controllers\Admin\PermissionAdminController;
use App\Http\Controllers\Admin\UserRoleAdminController;
use App\Http\Controllers\Admin\PermissionMatrixController;
use App\Http\Controllers\SettingsController;

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

    // POS - Admin, Manager, Cashier
    Route::middleware('role:Admin|Manager|Cashier')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/cart/add', [PosController::class, 'add'])->name('pos.cart.add');
        Route::post('/pos/cart/add-menu', [PosController::class, 'addMenu'])->name('pos.cart.addMenu');
        Route::post('/pos/cart/update', [PosController::class, 'update'])->name('pos.cart.update');
        Route::post('/pos/cart/remove', [PosController::class, 'remove'])->name('pos.cart.remove');
        Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
        Route::post('/pos/payments', [PaymentController::class, 'store'])->name('pos.payments.store');
        Route::get('/pos/receipt/{order}', [PaymentController::class, 'receipt'])->name('pos.receipt');
        Route::get('/pos/receipt/{order}/thermal', [PaymentController::class, 'receiptThermal'])->name('pos.receipt.thermal');
    });

    // Rooms - Admin, Manager
    Route::middleware('role:Admin|Manager')->group(function () {
        Route::resource('rooms', RoomController::class)->except(['show']);

        // Bookings (check-in / check-out)
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::post('bookings/{booking}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');

        // Restaurant CRUD: Menu Categories and Items
        Route::resource('menu-categories', MenuCategoryController::class)->except(['show']);
        Route::resource('menu-items', MenuItemController::class)->except(['show']);
    });

    // Kitchen (KOT) - Kitchen Staff, Manager, Admin
    Route::middleware('role:Kitchen Staff|Manager|Admin')->group(function () {
        Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
        Route::post('/kitchen/orders/{order}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.orders.status');
    });

    // Restaurant - Admin, Manager, Kitchen Staff, Cashier
    Route::middleware('role:Admin|Manager|Kitchen Staff|Cashier')->group(function () {
        Route::get('/restaurant', fn () => view('restaurant'))->name('restaurant.index');
    });

    // Inventory - Admin, Manager, Kitchen Staff
    Route::middleware('role:Admin|Manager|Kitchen Staff')->prefix('inventory')->as('inventory.')->group(function () {
        Route::get('/', fn () => redirect()->route('inventory.products.index'))->name('index');
        Route::resource('categories', InventoryCategoryController::class)->parameters(['categories' => 'product_category'])->except(['show']);
        Route::resource('products', InventoryProductController::class)->parameters(['products' => 'product'])->except(['show']);
        Route::get('movements', [StockMovementController::class, 'index'])->name('movements.index');
        // Purchase Management
        Route::resource('suppliers', App\Http\Controllers\SupplierController::class)->except(['show']);
        Route::resource('purchases', App\Http\Controllers\PurchaseController::class)->only(['index','create','store','show']);
    });

    // Reports - Admin, Manager
    Route::middleware('role:Admin|Manager')->group(function () {
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily-sales', [ReportsController::class, 'dailySales'])->name('reports.daily');
        Route::get('/reports/inventory-movement', [ReportsController::class, 'inventoryMovement'])->name('reports.inventory');
    });

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
        Route::post('/admin/users', [\App\Http\Controllers\Admin\UserAdminController::class, 'store'])->name('admin.users.store');
    });

    // Settings - Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';
