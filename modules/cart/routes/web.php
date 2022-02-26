<?php

Route::post('add_cart', 'CartController@add_cart');
Route::get('cart', 'CartController@cart');
Route::get('get_count_cart', 'CartController@get_count_cart');
Route::post('del_cart/{id}', 'CartController@del_cart');
