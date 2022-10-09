<?php

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
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


    // auth sanctum
    Route::group(["middleware" => "auth:sanctum"], function () {
        Route::as("auth.")->prefix("auth")->group(function () {
            Route::put("/user/update", [RegisterUserController::class, "update"])->name("update");
            Route::get("/user", [RegisterUserController::class, "update"])->name("show");
        });
    });
});
