<?php

Route::prefix('admin')->middleware(['auth','user'])->group(function (){
    Route::resource('orders', 'OrdersController')->except('create','edit','store');
});

Route::middleware(['auth', 'user'])->group(function () {
    Route::post( 'send_discount', 'OrderController@send_discount');
    Route::get( 'add_order', 'OrderController@add_order');
    Route::get( 'profile/order', 'OrderController@order');
    Route::get( 'profile/order/{order_id}', 'OrderController@show_order');
    Route::get( 'download', 'OrderController@download');
});


