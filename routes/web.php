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

Route::get('/online-game/{locale}', function () {
    return view('logged.online-game');
});

Route::middleware('auth')->group(function () {
    Route::post('/mistake/{locale}', [MistakeController::class, 'store']);
    Route::get('/mistake/{locale}', [MistakeController::class, 'create']);
    Route::post('/suggestion/{locale}', [SuggestionController::class, 'store']);
    Route::get('/suggestion/{locale}', [SuggestionController::class, 'create']);
    Route::get('/check-room/{locale}' , [OnlineGameController::class, 'checkOnLoad']);
    Route::get('/online-room/{locale}' , [OnlineGameController::class, 'online']);
    Route::post('/online-room/{locale}' , [OnlineGameController::class, 'listen']);
    Route::post('/create-room/{locale}' , [OnlineGameController::class, 'create']);
    Route::post('/close-room/{locale}' , [OnlineGameController::class, 'close']);
    Route::post('/join-room/{locale}' , [OnlineGameController::class, 'join']);
    Route::post('/kick-room/{locale}' , [OnlineGameController::class, 'kick']);
    Route::post('/exit-room/{locale}' , [OnlineGameController::class, 'exit']);
});

Route::get('/dashboard/{locale?}', function () {
    return view('dashboard');
})->middleware(['locale', 'auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
