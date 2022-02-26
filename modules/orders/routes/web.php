<?php

Route::prefix('admin')->middleware(['auth','user'])->group(function (){
    create_crud_route('orders','OrdersController',['create','store','edit','update']);
});

