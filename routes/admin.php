<?php

Route::middleware(['auth','user'])->group(function (){

   // Route::get('/panel','AdminController@author_panel')->name('author_panel');
   // Route::get('/403','AdminController@error403')->name('error403');

});
