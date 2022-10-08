<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('logout'); //view
Route::group(['middleware' => ['auth','block']],function () {

        Route::get('/', 'OrdersController@index')->name('order.index');
        Route::get('/order-list', 'OrdersController@list')->name('order.list');
        Route::get('/order', 'OrdersController@create')->name('order.from');
        Route::post('/orders', 'OrdersController@store')->name('order.store');

        Route::get('/profile', 'Auth\ProfilesController@index')->name('profiles.index');
        Route::Post('/profile', 'Auth\ProfilesController@store')->name('profiles.store');
        Route::get('/change-password', 'Auth\ChangePasswordController@index')->name('change_passwords.index');

        Route::fallback(function () {
            return view('errors.404');
        });
    }
);

Route::get('/testsap', 'testController@testsap')->name('test.testsap');

Route::get('/change-password/{token}', 'Auth\ChangePasswordController@forceChangPassword')->name('changePasswords.forceChangPassword')->middleware('auth');

Route::Post('/change-password', 'Auth\ChangePasswordController@store')->name('changePasswords.store')->middleware('auth');

// Route::get('/', function () {
//     return view('errors.404');
// });
// Route::get('/login', function () {
//     return view('errors.404');
// });