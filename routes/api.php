<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SlotsController;
use App\Http\Controllers\BidsController;


Route::post('/user/create', [UserController::class, 'create']);
Route::post('/user/login',  [UserController::class, 'login']);
Route::post('/user/logout', [UserController::class, 'logout']);
Route::get('/user/{userId}/bids', [UserController::class, 'userBidsHistory'])->middleware('auth:sanctum');
// List all slots with pagination using authenticated user

Route::get('/slots', [SlotsController::class, 'index'])->middleware('auth:sanctum');
Route::post('/bids', [BidsController::class, 'create'])->middleware('auth:sanctum');
//view winning bid
Route::get('/slots/{slotId}/bids', [SlotsController::class, 'viewWinningBid'])->middleware('auth:sanctum');
Route::get('/bids/{slotId}', [BidsController::class, 'index'])->middleware('auth:sanctum');
