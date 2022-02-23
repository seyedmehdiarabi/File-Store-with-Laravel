<?php

Route::middleware(['auth','user'])->group(function (){
    Route::match(['get','post'],'setting/shop','SettingController@shop')->name('shop.setting');
});
