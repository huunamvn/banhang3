<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\CatelogueController;
use App\Http\Controllers\client\HomeControllers;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('admin')->middleware(['auth','role:admin|user2|admin4|Super Admin'])
    ->as('admin.')
    ->group(function () {
        Route::get('/', function () {
            return view('admin/dashboard');
        });
        // route danh mục
        Route::resource('/catelogue', CatelogueController::class);
        Route::get('index', [CatelogueController::class, 'index'])->middleware('can:Show category')->name('index');
        Route::get('create', [CatelogueController::class, 'create'])->name('create');
        Route::post('store', [CatelogueController::class, 'store'])->name('store');
        Route::get('show/{id}', [CatelogueController::class, 'show'])->name('show');
        Route::get('{id}/edit', [CatelogueController::class, 'edit'])->middleware('can:edit Category')->name('edit');
        Route::put('update/{id}', [CatelogueController::class, 'update'])->name('update');
        Route::get('{id}/destroy', [CatelogueController::class, 'destroy'])->name('destroy');


        // Route product
        Route::resource('/product', ProductController::class);
        Route::get('index', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::get('show/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [ProductController::class, 'update'])->name('update');
        Route::get('{id}/destroy', [ProductController::class, 'destroy'])->name('destroy');

// route user 
        Route::get('/phanvaitro/{id}', [UserController::class, 'phanvaitro']);
        Route::get('/phanquyen/{id}', [UserController::class, 'phanquyen']);
        Route::post('/insert_roles/{id}', [UserController::class, 'insert_roles']);
        Route::post('/insert_permission/{id}', [UserController::class, 'insert_permission']);
        Route::post('/insertPermission', [UserController::class, 'insertPermission']);
        Route::resource('/users', UserController::class);
        Route::get('/impersonate/users/{id}', [UserController::class, 'impersonate']);
    });

Route::get('/', [HomeControllers::class, 'index'])->name('index');


Route::prefix('client')
    ->as('client.')
    ->group(function () {
        // Route::resource('/client',ProductController::class);

        Route::get('product/{id}', [HomeControllers::class, 'product'])->name('product');
        Route::get('create/{id}', [HomeControllers::class, 'create'])->name('create');
        Route::get('/', [HomeControllers::class, 'index'])->name('index');
        Route::get('listCart', [HomeControllers::class, 'listCart'])->name('listCart');
        Route::post('cart', [HomeControllers::class, 'cart'])->name('cart');
        Route::delete('cart/{id}', [HomeControllers::class, 'destroy'])->name('cart.destroy');
        // Hiển thị trang thanh toán
        Route::get('checkout', [HomeControllers::class, 'checkout'])->name('checkout');
        Route::post('checkout', [HomeControllers::class, 'processCheckout'])->name('checkout.process');
        Route::get('checkout/success', [HomeControllers::class, 'checkoutSuccess'])->name('checkout.success');
        // Route in hóa đơn
        Route::get('/print-bill', [HomeControllers::class, 'printBill'])->name('client.printBill');
        Route::get('/checkout-success/{bill_id}', [HomeControllers::class, 'checkoutSuccess'])->name('client.checkout.success');

        Route::get('/my-orders', [HomeControllers::class, 'myOrders'])->name('client.myOrders');

        Route::get('/order-details/{id}', [HomeControllers::class, 'orderDetails'])->name('client.orderDetails');
        Route::get('/bill/{billId}', [HomeControllers::class, 'showBill'])->name('client.showBill');

        Route::get('/orders', [HomeControllers::class, 'showOrders'])->name('client.showOrders');
    });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
