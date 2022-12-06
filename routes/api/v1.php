<?php

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\UserInformationController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodImagesController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;




Route::post("/login", [AuthenticateUserController::class, "login"])->name("login");
Route::post("/register", [RegisterUserController::class, "store"])->name("register");

Route::get("/foods", [FoodController::class, "index"])->name("get.food");
Route::post("/checkout", [TransactionController::class, "checkout"])->name("checkout");
Route::get("/store", [StoreController::class, "index"])->name("store.index");
Route::get("/check-email", [AuthenticateUserController::class, "checkEmailAvailable"])->name("check-email");

// should authenticated
Route::group(["middleware" => "auth:sanctum"], function () {
    Route::as("auth.")->prefix("auth")->group(function () {
        Route::put("/user/update", [UserInformationController::class, "update"])->name("update");
        Route::post("/user/upload-photo", [UserInformationController::class, "uploadAvatar"])->name("update.photo");
        Route::get("/me", [UserInformationController::class, "show"])->name("show");
    });

    Route::apiResource("store", StoreController::class)->except("index", "show");
    Route::apiResource("foods", FoodController::class)->except("index", "show");
    Route::post("/foods/upload-photo/{food}", [FoodImagesController::class, "store"])->name(".food.upload-images");
    Route::get("/transactions", [TransactionController::class, "all"])->name("get.transaction");
    Route::put("/transactions/{id}", [TransactionController::class, "update"])->name("update.transaction");
    Route::post("/transactions/checkout", [TransactionController::class, "checkout"])->name("create.transaction");
    Route::delete("/logout", [AuthenticateUserController::class, "logout"])->name("logout");
});

Route::any("/trx/notifications", [MidtransCallbackController::class, "callback"])->name("midtrans.notif");
