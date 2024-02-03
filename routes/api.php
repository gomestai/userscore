<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

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
Route::group(['prefix' => '/user'], function () {
    Route::post('/start-game', [GameController::class, 'startGame']);
    Route::post('/end-game', [GameController::class, 'endGame']);
    Route::get('/topTen', [GameController::class, 'topTen']);
});