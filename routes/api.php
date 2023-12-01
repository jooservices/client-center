<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CrawlingController;
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

Route::prefix('clients')
    ->name('clients.')
    ->group(function () {
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');

    });

Route::prefix('crawling')
    ->name('crawling.')
    ->group(function () {
        Route::post('/', [CrawlingController::class, 'store'])->name('store');
    });
