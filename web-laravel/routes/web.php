<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController; // <-- add this
use App\Http\Controllers\SellerOnboardingController;
// Root -> login
Route::get('/', fn () => redirect()->route('login'));
Route::get('/home', function () {
    $u = Auth::user();
    if (!$u) return redirect()->route('login');

    return match (strtolower($u->role)) {
        'customer'         => redirect()->route('customer.products.index'),
        'farmer'           => redirect()->route('farmer.products.index'),
        'cooperativeadmin' => redirect()->route('admin.dashboard'),
        default            => redirect()->route('login'),
    };
})->name('home')->middleware('auth');

// optional: make "/" smart too
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});

// SELL / BECOME A SELLER
Route::prefix('sell')->name('sell.')->group(function () {
    Route::get('/shop-info', [SellerOnboardingController::class, 'showShopInfoForm'])->name('shop');
    Route::post('/shop-info', [SellerOnboardingController::class, 'submitShopInfo'])->name('shop.submit');

    Route::get('/business-info', [SellerOnboardingController::class, 'showBusinessInfoForm'])->name('business');
    Route::post('/business-info', [SellerOnboardingController::class, 'submitBusinessInfo'])->name('business.submit');

    Route::get('/pending', [SellerOnboardingController::class, 'pending'])->name('pending');
});

// Guest routes
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Customer routes
    Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/products', [CustomerController::class, 'showProducts'])->name('products.index');
    Route::get('/farmers/{farmerid}', [CustomerController::class, 'viewFarmerProfile'])->name('farmers.show');

    Route::post('/order', [CustomerController::class, 'placeOrder'])->name('order.place');

    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{orderid}', [CustomerController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{orderid}/cancel', [CustomerController::class, 'cancelOrder'])->name('orders.cancel');

    Route::get('/settings', [CustomerController::class, 'settings'])->name('settings');
    Route::post('/settings', [CustomerController::class, 'updateSettings'])->name('settings.update');

    Route::get('/notifications', [CustomerController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{id}/read', [CustomerController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/clear', [CustomerController::class, 'clearNotifications'])->name('notifications.clear');

    Route::post('/review/{productID}', [CustomerController::class, 'reviewProduct'])->name('review.store');
    // routes/web.php (inside the existing Route::prefix('customer')->name('customer.')->group(...))

// Settings
Route::get('/settings', [CustomerController::class, 'settings'])->name('settings.index');
Route::post('/settings', [CustomerController::class, 'updateSettings'])->name('settings.update');

// Notifications
Route::get('/notifications', [CustomerController::class, 'notifications'])->name('notifications.index');
Route::post('/notifications/{id}/read', [CustomerController::class, 'markNotificationRead'])->name('notifications.read');
Route::post('/notifications/clear', [CustomerController::class, 'clearNotifications'])->name('notifications.clear');

    });

    // Farmer routes
    Route::prefix('farmer')->name('farmer.')->group(function () {
        Route::get('/products', [FarmerController::class, 'showProducts'])->name('products.index');
        Route::post('/product/create', [FarmerController::class, 'createProduct'])->name('products.create');
        Route::get('/profile', [FarmerController::class, 'editProfile'])->name('farmer.profile.edit');
        Route::post('/profile', [FarmerController::class, 'updateProfile'])->name('farmer.profile.update');
        // if you added extended features earlier, keep these too:
        Route::get('/product/{productid}/edit', [FarmerController::class, 'editProductForm'])->name('products.edit');
        Route::patch('/product/{productid}', [FarmerController::class, 'updateProduct'])->name('products.update');
        Route::delete('/product/{productid}', [FarmerController::class, 'deleteProduct'])->name('products.delete');
        Route::patch('/product/{productid}/inventory', [FarmerController::class, 'adjustInventory'])->name('inventory.adjust');
        Route::post('/inventory/bulk-restock', [FarmerController::class, 'bulkRestock'])->name('inventory.bulk');
        Route::get('/low-stock', [FarmerController::class, 'lowStock'])->name('products.lowstock');
        Route::get('/orders', [FarmerController::class, 'orders'])->name('orders.index');
        Route::patch('/orders/{orderid}/confirm', [FarmerController::class, 'confirmDelivery'])->name('orders.confirm');
        Route::get('/sales-report', [FarmerController::class, 'salesReport'])->name('reports.sales');
        Route::get('/profile', [FarmerController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile', [FarmerController::class, 'updateProfile'])->name('profile.update');

        //shopinfo, businessinfo 
        Route::get('/shop-info', [FarmerController::class, 'showShopInfo'])->name('shop.info');
        Route::post('/shop-info', [FarmerController::class, 'updateShopInfo'])->name('shop.info.update');
    });

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'showDashboard'])->name('dashboard');
        // In your existing admin group
Route::get('/farmers/applications', [AdminController::class, 'farmerApplications'])->name('farmers.applications');
Route::post('/farmers/{farmerid}/approve', [AdminController::class, 'approveFarmer'])->name('farmers.approve');
Route::post('/farmers/{farmerid}/reject', [AdminController::class, 'rejectFarmer'])->name('farmers.reject');

    });

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
