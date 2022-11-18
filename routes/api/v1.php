<?php

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\UserInformationController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;




Route::post("/login", [AuthenticateUserController::class, "login"])->name("login");
Route::post("/register", [RegisterUserController::class, "store"])->name("register");

Route::get("/foods", [FoodController::class, "index"])->name("get.food");
Route::post("/checkout", [TransactionController::class, "checkout"])->name("checkout");

// should authenticated
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
