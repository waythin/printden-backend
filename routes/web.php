<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingsController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::fallback(function () {
	$title = 'Error 404';
	if(isset(Auth::guard('admin')->user()->id)){
		return view('admin.errors.404')->with(compact('title'));
	}
	else{
		return view('front.errors.404')->with(compact('title'));
	}
});
Route::match(['get', 'post'], '/forget-password', [AuthController::class, 'forgetPassword'])->name('forget.pass');
Route::match(['get', 'post'], '/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('reset.pass');
Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function () {

	Route::group(['middleware' => ['IsAdmin']], function () { 
        //Check Admin Password
		Route::post('check-admin-password', [AuthController::class, 'checkAdminPassword']);
		// Change Admin Password
		Route::post('change-admin-password', [AuthController::class, 'changeAdminPassword'])->name('admin.change.password');
		// Update Admin Details
		Route::match(['get', 'post'], 'update-admin-details', [AdminController::class, 'updateAdminDetails'])->name('admin.update.details');
        //Admin Dashboard Route
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::match(['get', 'post'], 'orders/orders-datatables/{type?}', [OrderController::class, 'ordersDatatables'])->name('admin.orders_datatables');//JSON REQUEST
		Route::get('order', [OrderController::class, 'orders'])->name('admin.orders');

        //Setting
		Route::get('settings/{type?}', [SettingsController::class, 'settings'])->name('admin.settings');
		
		//Admin Logout Route
		Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    });

});

