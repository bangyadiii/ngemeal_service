<?php

use App\Events\UserOrderMessage;
use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\UserInformationController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$routeAttributes = [
    "prefix" => "/v1",
    "as" => "api."
];

Route::group($routeAttributes, function () {
    Route::post("/login", [AuthenticateUserController::class, "login"])->name("login");
    Route::post("/register", [RegisterUserController::class, "store"])->name("login");

    Route::get("/foods", [FoodController::class, "index"])->name("get.food");
    Route::post("/checkout", [TransactionController::class, "checkout"])->name("checkout");

    // auth sanctum
    Route::group(["middleware" => "auth:sanctum"], function () {
        Route::as("auth.")->prefix("auth")->group(function () {
            Route::put("/user/update", [UserInformationController::class, "update"])->name("update");
            Route::get("/me", [UserInformationController::class, "show"])->name("show");
        });

        Route::apiResource("store", StoreController::class)->except("index", "show");
        Route::apiResource("foods", FoodController::class)->except("index", "show");
        Route::get("/transactions", [TransactionController::class, "all"])->name("get.transaction");
        Route::put("/transactions/{id}", [TransactionController::class, "update"])->name("update.transaction");
    });
});



Route::group(["as" => "chats", "prefix" => "chats"], function () {
    Route::get('/send', function (Request $request) {
        UserOrderMessage::dispatch("HELLIWW");
        return "Hellow";
    });
});
