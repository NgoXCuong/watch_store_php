<?php
use Core\Router;
use App\Controllers\Client\HomeController;
use App\Controllers\Client\ProductsController;
use App\Controllers\Client\CartController;
use App\Controllers\Client\AuthController;
use App\Controllers\Client\ProfileController;
use App\Controllers\Client\CheckoutController;
use App\Controllers\Client\OrdersController;
use App\Controllers\Client\ReviewsController;
use App\Controllers\Client\AboutController;

use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ProductsController as AdminProductsController;
use App\Controllers\Admin\CategoriesController as AdminCategoriesController;
use App\Controllers\Admin\BrandsController as AdminBrandsController;
use App\Controllers\Admin\UsersController as AdminUsersController;
use App\Controllers\Admin\OrdersController as AdminOrdersController;
use App\Controllers\Admin\ReviewsController as AdminReviewsController;
use App\Controllers\Admin\VouchersController as AdminVouchersController;
use App\Controllers\Admin\AnalyticsController as AdminAnalyticsController;

$router = new Router();

// ==============================================================================
// CLIENT ROUTES
// ==============================================================================

// Home
$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);

// Auth
$router->get('/auth/login', [AuthController::class, 'login']);
$router->post('/auth/login-process', [AuthController::class, 'loginProcess']); // Changed from convention format to explicit
$router->get('/auth/register', [AuthController::class, 'register']);
$router->post('/auth/register-process', [AuthController::class, 'registerProcess']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

// Products
$router->get('/products', [ProductsController::class, 'index']);
$router->get('/products/show/{id}', [ProductsController::class, 'show']);
$router->get('/products/search', [ProductsController::class, 'search']);

// Cart
$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add']);
$router->post('/cart/update', [CartController::class, 'update']);
$router->get('/cart/remove/{id}', [CartController::class, 'remove']);
$router->get('/cart/clear', [CartController::class, 'clear']);
$router->get('/cart/count', [CartController::class, 'count']);

// Checkout
$router->get('/checkout', [CheckoutController::class, 'index']);
$router->post('/checkout/process', [CheckoutController::class, 'process']);
$router->post('/checkout/apply-voucher', [CheckoutController::class, 'applyVoucher']); // API method
$router->get('/checkout/vnpayReturn', [CheckoutController::class, 'vnpayReturn']); // VNPay Return URL
$router->get('/checkout/success/{id}', [CheckoutController::class, 'success']);

// Profile
$router->get('/profile', [ProfileController::class, 'index']);
$router->get('/profile/edit', [ProfileController::class, 'edit']);
$router->post('/profile/update', [ProfileController::class, 'update']);
$router->get('/profile/change-password', [ProfileController::class, 'changePassword']);
$router->post('/profile/update-password', [ProfileController::class, 'updatePassword']);

// Orders (Client)
$router->get('/orders', [OrdersController::class, 'index']);
$router->get('/orders/show/{id}', [OrdersController::class, 'show']);
$router->post('/orders/cancel/{id}', [OrdersController::class, 'cancel']);

// Reviews
$router->get('/reviews/create/{id}', [ReviewsController::class, 'create']);
$router->post('/reviews/store', [ReviewsController::class, 'store']);
$router->get('/reviews/my-reviews', [ReviewsController::class, 'myReviews']);

// About
$router->get('/about', [AboutController::class, 'index']);

// Brands
$router->get('/brands', [\App\Controllers\Client\BrandsController::class, 'index']);


// ==============================================================================
// ADMIN ROUTES
// ==============================================================================

// Dashboard
$router->get('/admin', [DashboardController::class, 'index']);
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

// Products
$router->get('/admin/products', [AdminProductsController::class, 'index']);
$router->get('/admin/products/create', [AdminProductsController::class, 'create']);
$router->post('/admin/products/store', [AdminProductsController::class, 'store']);
$router->get('/admin/products/show/{id}', [AdminProductsController::class, 'show']);
$router->get('/admin/products/edit/{id}', [AdminProductsController::class, 'edit']);
$router->post('/admin/products/update/{id}', [AdminProductsController::class, 'update']);
$router->post('/admin/products/delete/{id}', [AdminProductsController::class, 'delete']);

// Categories
$router->get('/admin/categories', [AdminCategoriesController::class, 'index']);
$router->get('/admin/categories/create', [AdminCategoriesController::class, 'create']);
$router->post('/admin/categories/store', [AdminCategoriesController::class, 'store']);
$router->get('/admin/categories/edit/{id}', [AdminCategoriesController::class, 'edit']);
$router->post('/admin/categories/update/{id}', [AdminCategoriesController::class, 'update']);
$router->post('/admin/categories/delete/{id}', [AdminCategoriesController::class, 'delete']);

// Brands
$router->get('/admin/brands', [AdminBrandsController::class, 'index']);
$router->get('/admin/brands/create', [AdminBrandsController::class, 'create']);
$router->post('/admin/brands/store', [AdminBrandsController::class, 'store']);
$router->get('/admin/brands/edit/{id}', [AdminBrandsController::class, 'edit']);
$router->post('/admin/brands/update/{id}', [AdminBrandsController::class, 'update']);
$router->post('/admin/brands/delete/{id}', [AdminBrandsController::class, 'delete']);

// Users
$router->get('/admin/users', [AdminUsersController::class, 'index']);
$router->get('/admin/users/create', [AdminUsersController::class, 'create']);
$router->post('/admin/users/store', [AdminUsersController::class, 'store']);
$router->get('/admin/users/edit/{id}', [AdminUsersController::class, 'edit']);
$router->post('/admin/users/update/{id}', [AdminUsersController::class, 'update']);
$router->post('/admin/users/delete/{id}', [AdminUsersController::class, 'delete']);

// Orders
$router->get('/admin/orders', [AdminOrdersController::class, 'index']);
$router->get('/admin/orders/show/{id}', [AdminOrdersController::class, 'show']);
$router->get('/admin/orders/get-order-details/{id}', [AdminOrdersController::class, 'getOrderDetails']); // AJAX
$router->post('/admin/orders/update-status/{id}', [AdminOrdersController::class, 'updateStatus']);
$router->post('/admin/orders/update-payment-status/{id}', [AdminOrdersController::class, 'updatePaymentStatus']);

// Reviews
$router->get('/admin/reviews', [AdminReviewsController::class, 'index']);
$router->get('/admin/reviews/show/{id}', [AdminReviewsController::class, 'show']);
$router->post('/admin/reviews/approve/{id}', [AdminReviewsController::class, 'approve']);
$router->post('/admin/reviews/delete/{id}', [AdminReviewsController::class, 'delete']);

// Vouchers
$router->get('/admin/vouchers', [AdminVouchersController::class, 'index']);
$router->get('/admin/vouchers/create', [AdminVouchersController::class, 'create']);
$router->post('/admin/vouchers/store', [AdminVouchersController::class, 'store']);
$router->get('/admin/vouchers/edit/{id}', [AdminVouchersController::class, 'edit']);
$router->post('/admin/vouchers/update/{id}', [AdminVouchersController::class, 'update']);
$router->post('/admin/vouchers/delete/{id}', [AdminVouchersController::class, 'delete']);

// Analytics
$router->get('/admin/analytics', [AdminAnalyticsController::class, 'index']);
$router->get('/admin/analytics/revenue', [AdminAnalyticsController::class, 'revenue']);
$router->get('/admin/analytics/products', [AdminAnalyticsController::class, 'products']);
$router->get('/admin/analytics/exportExcel', [AdminAnalyticsController::class, 'exportExcel']);



return $router;
