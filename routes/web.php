<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRestaurantController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminFoodController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminDeliveryStaffController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminDeliveryController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminExpenseController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Delivery\DeliveryDashboardController;
use App\Http\Controllers\Delivery\DeliveryOrderController;

// Customer Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/foods', [FoodController::class, 'index'])->name('foods.index');
Route::get('/foods/{food}', [FoodController::class, 'show'])->name('foods.show');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::get('/orders/{order}/receipt/download', [OrderController::class, 'receiptDownload'])->name('orders.receipt.download');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');
});

// Notification Routes (accessible by any authenticated user)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/{notification}/read-and-redirect', [NotificationController::class, 'readAndRedirect'])->name('notifications.readAndRedirect');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Page Routes
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::post('/about', [PageController::class, 'storeInquiry'])->name('about.store');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'storeContact'])->name('contact.store');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth Routes
Route::get('/auth/google', [SocialAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [SocialAuthController::class, 'callback'])->name('google.callback');

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/restaurant', [AdminRestaurantController::class, 'index'])->name('restaurant.index');
    Route::post('/restaurant', [AdminRestaurantController::class, 'update'])->name('restaurant.update');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::get('/categories/grid', [AdminCategoryController::class, 'grid'])->name('categories.grid');
    Route::resource('foods', AdminFoodController::class)->except('show');
    Route::get('/foods/grid', [AdminFoodController::class, 'grid'])->name('foods.grid');
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::resource('delivery-staff', AdminDeliveryStaffController::class);
    Route::post('/delivery-staff/{delivery_staff}/toggle', [AdminDeliveryStaffController::class, 'toggleStatus'])->name('delivery-staff.toggle');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/grid', [AdminOrderController::class, 'grid'])->name('orders.grid');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/receipt', [AdminOrderController::class, 'receipt'])->name('orders.receipt');
    Route::get('/orders/{order}/receipt/download', [AdminOrderController::class, 'receiptDownload'])->name('orders.receipt.download');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/deliveries', [AdminDeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/grid', [AdminDeliveryController::class, 'grid'])->name('deliveries.grid');
    Route::get('/deliveries/{delivery}', [AdminDeliveryController::class, 'show'])->name('deliveries.show');
    Route::post('/deliveries/{order}/assign', [AdminDeliveryController::class, 'assign'])->name('deliveries.assign');
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::resource('coupons', AdminCouponController::class);
    Route::post('/coupons/{coupon}/toggle', [AdminCouponController::class, 'toggleStatus'])->name('coupons.toggle');
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/reply', [AdminReviewController::class, 'reply'])->name('reviews.reply');
    Route::post('/reviews/{review}/toggle', [AdminReviewController::class, 'toggle'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::resource('expenses', AdminExpenseController::class);
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [AdminReportController::class, 'print'])->name('reports.print');
    Route::get('/reports/export', [AdminReportController::class, 'exportExcel'])->name('reports.export');
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/income', [AdminDashboardController::class, 'income'])->name('income.index');
    Route::post('/quick-store', [AdminDashboardController::class, 'quickStore'])->name('quick-store');
});

// Delivery Routes
Route::middleware(['auth', 'delivery'])->prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/dashboard', [DeliveryDashboardController::class, 'index'])->name('dashboard');
    Route::get('/report', [DeliveryDashboardController::class, 'report'])->name('report');
    Route::get('/report/print', [DeliveryDashboardController::class, 'printReport'])->name('report.print');
    Route::get('/report/export', [DeliveryDashboardController::class, 'exportExcel'])->name('report.export');
    Route::get('/orders', [DeliveryOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [DeliveryOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/receipt', [DeliveryOrderController::class, 'receipt'])->name('orders.receipt');
    Route::get('/orders/{order}/receipt/download', [DeliveryOrderController::class, 'receiptDownload'])->name('orders.receipt.download');
    Route::post('/orders/{order}/accept', [DeliveryOrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/pickup', [DeliveryOrderController::class, 'pickup'])->name('orders.pickup');
    Route::post('/orders/{order}/out-for-delivery', [DeliveryOrderController::class, 'outForDelivery'])->name('orders.outForDelivery');
    Route::post('/orders/{order}/delivered', [DeliveryOrderController::class, 'delivered'])->name('orders.delivered');
    Route::post('/orders/{order}/deliver-and-pay', [DeliveryOrderController::class, 'deliverAndPay'])->name('orders.deliverAndPay');
    Route::post('/orders/{order}/mark-paid', [DeliveryOrderController::class, 'markPaid'])->name('orders.markPaid');
    Route::get('/profile', [ProfileController::class, 'deliveryProfile'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'deliveryProfileUpdate'])->name('profile.update');
});

Route::get('/debug-db', function () {
    $url = env('DATABASE_URL');
    $host = parse_url($url, PHP_URL_HOST);
    $start = microtime(true);
    $sock = @fsockopen($host, 5432, $errno, $errstr, 5);
    return [
        'host' => $host,
        'options' => parse_url($url, PHP_URL_QUERY),
        'can_reach_neon' => (bool) $sock,
        'error' => $errstr,
        'seconds' => round(microtime(true) - $start, 2),
    ];
});