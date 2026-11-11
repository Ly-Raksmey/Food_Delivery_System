<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
| BASE URL: http://localhost:8000/api
|
| Authentication: Pass Bearer token in the Authorization header.
|   Header: Authorization: Bearer <token>
|   Obtain a token from POST /api/login or POST /api/register.
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::post('/cart/update', [CartController::class, 'update']);
    Route::post('/cart/remove', [CartController::class, 'remove']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/foods', [FoodController::class, 'index']);
Route::get('/foods/{food}', [FoodController::class, 'show']);

Route::get('/telegram/test', function (\App\Services\TelegramService $telegram) {
    $chatId = config('services.telegram.admin_chat_id');
    $token = config('services.telegram.bot_token');

    if (empty($token)) {
        return response()->json(['error' => 'Bot token not configured'], 500);
    }

    $message = "Test message from Food Delivery System\n\nBot Token: " . substr($token, 0, 10) . "...\nChat ID: " . $chatId;

    $sent = $telegram->sendMessage($chatId, $message);

    return response()->json([
        'sent' => $sent,
        'chat_id' => $chatId,
        'bot_token_prefix' => substr($token, 0, 10)
    ]);
});
