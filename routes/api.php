<?php

use App\Http\Controllers\Api\V1\HouseController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\LocatorController;
use App\Http\Controllers\Api\V1\ProprietorController;
use App\Http\Controllers\Api\V1\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/

###___
Route::prefix('v1')->group(function () {
    Route::prefix('immo')->group(function () {
        Route::prefix("house")->group(function () {
            Route::controller(HouseController::class)->group(function () {
                Route::any('{id}/retrieve', 'RetrieveHouse'); #RECUPERATION D'UNE MAISON
            });
        });
        ##___

        Route::prefix("room")->group(function () {
            Route::controller(RoomController::class)->group(function () {
                Route::any('{id}/retrieve', 'RetrieveRoom'); #RECUPERATION D'UNE CHAMBRE
            });
        });
        ##___

        Route::prefix("location")->group(function () {
            Route::controller(LocationController::class)->group(function () {
                Route::any('{id}/retrieve', 'RetrieveLocation'); #RECUPERATION D'UNE LOCATION
            });
        });
        ##___

        Route::prefix("proprietor")->group(function () {
            Route::controller(ProprietorController::class)->group(function () {
                Route::any('{id}/retrieve', 'RetrieveProprietor'); #RECUPERATION D'UN PROPRIETAIRE
            });
        });
        ##___

        Route::prefix("locator")->group(function () {
            Route::controller(LocatorController::class)->group(function () {
                Route::any('{id}/retrieve', 'RetrieveLocator'); #RECUPERATION D'UN LOCATAIRE
            });
        });
        ##___
    });
});
