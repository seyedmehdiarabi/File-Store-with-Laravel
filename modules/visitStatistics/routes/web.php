<?php
Route::middleware(['auth','user'])->group(function (){
    Route::get('statistics/month/visit','StatisticsController@month_visit');
    Route::get('statistics/week/visit/{year?}','StatisticsController@week_visit');
    Route::get('product/visit/{product_id}','StatisticsController@product_visit_view')->name('product.visit');
    Route::get('statistics/product/{product_id}/visit','StatisticsController@get_product_visit');
});
