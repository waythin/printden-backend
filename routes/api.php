<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/size/list', [ServiceController::class, 'sizeList']);


Route::get('/frame/list', [ServiceController::class, 'frameList']);
Route::get('/album/list', [ServiceController::class, 'albumList']);


Route::post('/service/submit', [ServiceController::class, 'servicePost']);
