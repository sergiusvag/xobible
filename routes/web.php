<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logged\MistakeController;
use App\Http\Controllers\Logged\SuggestionController;
use App\Http\Controllers\Logged\OnlineGameController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', static function () {
    return redirect('/welcome/'. app()->getLocale());
});

Route::get('/welcome', static function () {
    return redirect('/welcome/'. app()->getLocale());
});

Route::get('/welcome/{locale}', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::post('/mistake/{locale}', [MistakeController::class, 'store']);
    Route::get('/mistake/{locale}', [MistakeController::class, 'create']);
    Route::post('/suggestion/{locale}', [SuggestionController::class, 'store']);
    Route::get('/suggestion/{locale}', [SuggestionController::class, 'create']);
    Route::get('/online-room/{locale}' , [OnlineGameController::class, 'create']);
    Route::post('/online-room/{locale}' , [OnlineGameController::class, 'listen']);
});

Route::get('/dashboard/{locale?}', function () {
    return view('dashboard');
})->middleware(['locale', 'auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
