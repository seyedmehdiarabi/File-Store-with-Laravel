<?php
Route::prefix('admin')->middleware(['auth','user'])->group(function (){
    Route::get('comments', 'CommentController@index')->name('comment.index');
    Route::delete('comments/{id}', 'CommentController@destroy');
    Route::post('comments/set_status', 'CommentController@set_status');
    Route::get('get_comments', 'CommentController@get_comments');
});

Route::post('add_comment', 'CommentController@add_comment');
Route::get('get_comment_product/{id}', 'CommentController@get_comment_product');
