<?php

use App\Http\Controllers\GatewayController;
use Illuminate\Support\Facades\Route;

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
    return view('home');
})->name('home');

Route::post('/deposit', [GatewayController::class, 'deposit'])->name('deposit');
Route::post('/deposit/callback', [GatewayController::class, 'callbackHandler'])->name('deposit.callback');

Route::get('/deposit/{id}/success', function () {
    return view('success');
})->name('deposit.success');

Route::get('/deposit/{id}/pending', function () {
    return view('pending');
})->name('deposit.pending');

