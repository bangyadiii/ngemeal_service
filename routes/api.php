<?php

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\FoodController;
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

    Route::get("/foods", [FoodController::class, "all"])->name("get.food");
    Route::get("/transactions", [TransactionController::class, "all"])->name("get.transaction");
    Route::put("/transactions/{id}", [TransactionController::class, "update"])->name("update.transaction");

    // auth sanctum
    Route::group(["middleware" => "auth:sanctum"], function () {
        Route::as("auth.")->prefix("auth")->group(function () {
            Route::put("/user/update", [RegisterUserController::class, "update"])->name("update");
            Route::get("/user", [RegisterUserController::class, "update"])->name("show");
        });
    });
});
