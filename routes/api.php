<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BkashController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });





Route::get('/service/list', [ServiceController::class, 'serviceList']);

Route::get('/print-type/list', [ServiceController::class, 'printTypeList']);
Route::get('/size/list', [ServiceController::class, 'sizeList']);


Route::get('/frame/list', [ServiceController::class, 'frameList']);
Route::get('/album/list', [ServiceController::class, 'albumList']);


Route::post('/service/submit', [ServiceController::class, 'servicePost']);


Route::post('/contact-us/submit', [AdminController::class, 'contactUsSubmit']);




  // Payment Routes for bKash
  Route::get('/bkash/payment', [App\Http\Controllers\BkashTokenizePaymentController::class,'index']);
  Route::get('/bkash/create-payment', [App\Http\Controllers\BkashTokenizePaymentController::class,'createPayment'])->name('bkash-create-payment');
  Route::get('/bkash/callback', [App\Http\Controllers\BkashTokenizePaymentController::class,'callBack'])->name('bkash-callBack');

  //search payment
  Route::get('/bkash/search/{trxID}', [App\Http\Controllers\BkashTokenizePaymentController::class,'searchTnx'])->name('bkash-serach');

  //refund payment routes
  Route::get('/bkash/refund', [App\Http\Controllers\BkashTokenizePaymentController::class,'refund'])->name('bkash-refund');
  Route::get('/bkash/refund/status', [App\Http\Controllers\BkashTokenizePaymentController::class,'refundStatus'])->name('bkash-refund-status');
