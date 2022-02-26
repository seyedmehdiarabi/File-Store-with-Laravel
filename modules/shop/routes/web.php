<?php

Route::get('/', 'SiteController@index')->name('home');
Route::get('product/{title}', 'SiteController@ShowProduct')->name('shop.show_product');


