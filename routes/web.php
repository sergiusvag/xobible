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

Route::post('/welcome/{locale}/mistake', [MistakeController::class, 'store']);
Route::get('/welcome/{locale}/mistake', [MistakeController::class, 'create'])
->middleware('auth');
Route::post('/welcome/{locale}/suggestion', [SuggestionController::class, 'store']);
Route::get('/welcome/{locale}/suggestion', [SuggestionController::class, 'create'])
->middleware('auth');

Route::get('/welcome/{ru}/online-room' , [OnlineGameController::class, 'create'])
->middleware('auth');
Route::post('/welcome/{ru}/online-room' , [OnlineGameController::class, 'listen'])
->middleware('auth');

Route::get('/dashboard/{locale?}', function () {
    return view('dashboard');
})->middleware(['locale', 'auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
