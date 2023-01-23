<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\FieldController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * SUB-LITE API ROUTES
 */

# Subscribers API
Route::prefix('subscribers')->group(function() { 
    Route::controller(SubscriberController::class)->group(function () {
        Route::get('/', 'index')->name('subscribers.index');
        Route::post('/', 'store')->name('subscribers.store');
        Route::get('/{id}', 'show')->name('subscribers.show');
        Route::put('/{subscriber}', 'update')->name('subscribers.update');
        Route::delete('/{id}', 'delete')->name('subscribers.delete');
    });
});

# Fields API
Route::prefix('fields')->group(function() {
    Route::controller(FieldController::class)->group(function () {
        Route::get('/', 'index')->name('fields.index');
        Route::post('/', 'store')->name('fields.store');
        Route::put('/{id}', 'update')->name('fields.update');
        Route::get('/{id}', 'show')->name('fields.show');
        Route::delete('/{id}', 'delete')->name('fields.delete');
    });
});