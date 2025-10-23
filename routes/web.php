<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\ReviewRatingsController;
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
	
			// review rating
			Route::get('review/review-datatables/{type?}', [ReviewRatingsController::class, 'reviewDatatables'])->name('admin.review_datatables');
			Route::get('review', [ReviewRatingsController::class, 'reviewList'])->name('admin.review');
			Route::post('review/update-review-status', [ReviewRatingsController::class, 'updateReviewStatus'])->name('admin.orders.updateReviewStatus');
			Route::post('review-post', [ReviewRatingsController::class, 'postReview'])->name('admin.post.review');
			Route::get('/delete-review/{id}', [ReviewRatingsController::class, 'deleteReview'])->name('admin.delete.review');
	
			// event
			Route::get('event/event-datatables/{type?}', [EventController::class, 'eventDatatables'])->name('admin.event_datatables');
			Route::get('event', [EventController::class, 'eventList'])->name('admin.event');
			Route::post('event/update-review-status', [EventController::class, 'updateEventStatus'])->name('admin.updateEventStatus');
			Route::post('event-post', [EventController::class, 'postEvent'])->name('admin.post.event');
			Route::get('/delete-event/{id}', [EventController::class, 'deleteEvent'])->name('admin.delete.event');

			// event category
			Route::get('event/category-datatables/{type?}', [EventCategoryController::class, 'categoryDatatables'])->name('admin.category_datatables');
			Route::get('category', [EventCategoryController::class, 'categoryList'])->name('admin.category');
			Route::post('category/update-review-status', [EventCategoryController::class, 'updateEventCategoryStatus'])->name('admin.updateCategoryStatus');
			Route::post('category-post', [EventCategoryController::class, 'postEventCategory'])->name('admin.post.category');
			Route::get('/delete-category/{id}', [EventCategoryController::class, 'deleteEventCategory'])->name('admin.delete.category');

			// offer

			Route::get('offers/list', [AdminController::class, 'offers'])->name('admin.offers');
			Route::match(['get', 'post'], 'add-edit-offer/{id?}', [AdminController::class, 'postOffer'])->name('admin.add_edit_offer');

			Route::post('update-offer-status', [AdminController::class, 'UpdateOfferStatus'])->name('admin.update_offer_status');
			Route::get('/delete-offer/{id}', [AdminController::class, 'deleteOffer'])->name('admin.delete_offer');

	
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

	

