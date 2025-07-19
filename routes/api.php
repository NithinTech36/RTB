<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SlotsController;
use App\Http\Controllers\BidsController;


Route::post('/user/create', [UserController::class, 'create']);
Route::post('/user/login',  [UserController::class, 'login']);
Route::post('/user/logout', [UserController::class, 'logout']);

// group routes for authenticated users
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/{userId}/bids', [UserController::class, 'userBidsHistory']);
    //group routes for slots and bids
    Route::get('/slots', [SlotsController::class, 'index']);
    Route::get('/slots/{slotId}/bids', [SlotsController::class, 'viewWinningBid']);
    Route::get('/bids/{slotId}', [BidsController::class, 'index']);
    Route::post('/bids', [BidsController::class, 'create']);

});

