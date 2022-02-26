<?php

Route::prefix('admin')->middleware(['auth','user'])->group(function (){
    create_crud_route('category','CategoryController');
});
