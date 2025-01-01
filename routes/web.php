<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\SettingsController;


Route::fallback(function () {
    $title = 'Error 404';

    if (Auth::guard('admin')->check()) {
        // If the admin user is authenticated, show the admin 404 page
        return view('admin.errors.404', compact('title'));
    } else {
        // Redirect unauthenticated users to the login route
        return redirect()->route('login');
    }
})->name('fallback');


Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');


Route::match(['get', 'post'], '/forget-password', [AuthController::class, 'forgetPassword'])->name('forget.pass');
Route::match(['get', 'post'], '/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('reset.pass');

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function () {
	Route::group(['middleware' => ['IsAdmin']], function () { 

		Route::group([], function () { 
	
			//Check Admin Password
			Route::post('check-admin-password', [AuthController::class, 'checkAdminPassword']);
			// Change Admin Password
			Route::post('change-admin-password', [AuthController::class, 'changeAdminPassword'])->name('admin.change.password');
			// Update Admin Details
			Route::match(['get', 'post'], 'update-admin-details', [AdminController::class, 'updateAdminDetails'])->name('admin.update.details');
			//Admin Dashboard Route
			Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
	
	
	
	
	
	
			Route::get('orders/orders-datatables/{type?}', [OrderController::class, 'ordersDatatables'])->name('admin.orders_datatables');
			Route::get('order', [OrderController::class, 'orders'])->name('admin.orders');
			Route::get('/download-zip/{order_id}', [OrderController::class, 'downloadZip'])->name('download.zip');
			Route::get('order-details/{id}', [OrderController::class, 'orderDetails'])->name('admin.order-details');
			Route::post('/admin/orders/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

			Route::post('/admin/orders/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('admin.orders.updatePaymentStatus');


			// customer
			Route::get('customers/datatables', [AdminController::class, 'customerDatatables'])->name('admin.customers_datatables');
			Route::get('admin/customers', [AdminController::class, 'customers'])->name('admin.customers');


			// contact us 
			Route::get('constact-datatables', [AdminController::class, 'contactDatatables'])->name('admin.contact_datatables');
			Route::get('contact/list', [AdminController::class, 'contactUsList'])->name('admin.contact.list');
			Route::post('/admin/contact/update-status', [AdminController::class, 'updateContactStatus'])->name('admin.contact.updateStatus');

	
	
			//Setting
			Route::get('settings/{type?}', [SettingsController::class, 'settings'])->name('admin.settings');
			
			//Admin Logout Route
			Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
		});
	
	});
});

	

