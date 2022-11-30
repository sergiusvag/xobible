<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logged\MistakeController;
use App\Http\Controllers\Logged\SuggestionController;
use App\Http\Controllers\Logged\OnlineRoomController;
use App\Http\Controllers\Logged\OnlineColorPickerController;
use App\Http\Controllers\Logged\OnlineGameController;
use App\Http\Controllers\WelcomeController;

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

Route::get('/welcome/{locale}', [WelcomeController::class, 'welcome']);


Route::middleware('auth')->group(function () {
    Route::post('/welcome-exit-game/{locale}', [WelcomeController::class, 'exitGame']);
    Route::post('/mistake/{locale}', [MistakeController::class, 'store']);
    Route::get('/mistake/{locale}', [MistakeController::class, 'index']);
    Route::post('/suggestion/{locale}', [SuggestionController::class, 'store']);
    Route::get('/suggestion/{locale}', [SuggestionController::class, 'index']);
    Route::get('/check-room/{locale}' , [OnlineRoomController::class, 'checkOnLoad']);
    Route::get('/online-room/{locale}' , [OnlineRoomController::class, 'index']);
    Route::post('/create-room/{locale}' , [OnlineRoomController::class, 'create']);
    Route::post('/close-room/{locale}' , [OnlineRoomController::class, 'close']);
    Route::post('/join-room/{locale}' , [OnlineRoomController::class, 'join']);
    Route::post('/kick-room/{locale}' , [OnlineRoomController::class, 'kick']);
    Route::post('/exit-room/{locale}' , [OnlineRoomController::class, 'exit']);
    Route::post('/start-room/{locale}' , [OnlineRoomController::class, 'start']);
    Route::get('/online-color-picker/{locale}', [OnlineColorPickerController::class, 'index']);
    Route::post('/online-game-start/{locale}', [OnlineColorPickerController::class, 'start']);
    Route::get('/online-game/{locale}', [OnlineGameController::class, 'index']);
    Route::get('/online-game-load/{locale}', [OnlineGameController::class, 'load']);
    Route::post('/online-game-tile-selected/{locale}', [OnlineGameController::class, 'tileSelected']);
    Route::post('/online-game-option-selected/{locale}', [OnlineGameController::class, 'optionSelected']);
    Route::post('/online-game-question-answered/{locale}', [OnlineGameController::class, 'questionAnswered']);
    Route::post('/online-game-close-result/{locale}', [OnlineGameController::class, 'closeResult']);
    Route::post('/online-game-over/{locale}', [OnlineGameController::class, 'gameOver']);
    Route::post('/online-game-next-round/{locale}', [OnlineGameController::class, 'nextRound']);
    Route::post('/online-game-next-round-join/{locale}', [OnlineGameController::class, 'newRoundJoin']);
    Route::post('/online-game-new-game/{locale}', [OnlineGameController::class, 'newGame']);
    Route::post('/online-game-finish-game/{locale}', [OnlineGameController::class, 'finishGame']);
});

Route::get('/dashboard/{locale?}', function () {
    return view('dashboard');
})->middleware(['locale', 'auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
