<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v2', 'middleware' => 'api'], function () {
    Route::Post('/chk-password', 'Auth\ChangePasswordController@checkPassword')->name('chk.pass');
    Route::Post('/chk-username', 'Auth\ProfilesController@checkUsername')->name('chk.username');
    Route::Post('/chk-email', 'Auth\ProfilesController@checkEmail')->name('chk.email');

    Route::Post('/chk-pathNo', 'OrdersController@checkPathNo')->name('chk.pathNo');
    Route::Post('/chk-serial', 'OrdersController@checkSerial')->name('chk.serial');

    Route::fallback(function () {
        return view('errors.404');
    });
});

