<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'oa'], function () {
    Route::get('/tester', 'OA\\FlowNotificationController@tester')
        ->name('oa:flowNotification:tester');

    Route::get('/adminStaff', 'OA\\FlowNotificationController@adminStaff')
        ->name('oa:flowNotification:adminStaff');

    Route::get('/tr2', 'OA\\FlowNotificationController@tr2')
        ->name('oa:flowNotification:tr2');
});

Route::group(['prefix' => 'stock'], function () {
    Route::get('/', 'Stock\\StockNotificationController@index')
        ->name('stock:flowNotification:index');
});

Route::group(['prefix' => 'japanese'], function () {
    Route::get('/', 'Japanese\\GegeController@index')
        ->name('stock:flowNotification:index');
});