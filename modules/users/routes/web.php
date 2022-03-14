<?php
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/admin', 'AdminController@index')->name('admin.index');
    create_crud_route('userRole', 'UserRoleController');
    Route::get('userRole/access/{role_id}', 'UserRoleController@access');
    Route::post('userRole/access/{role_id}', 'UserRoleController@add_access');
    create_crud_route('users', 'UsersController', []);
    Route::match(['get', 'post'], 'user/sms/channel', 'UsersController@sms_setting');


    Route::get('profile', 'UserPanelController@index');
    Route::post( 'profile/edit_profile', 'UserPanelController@edit_profile');


});

Auth::routes();
Route::middleware('guest')->group(function (){
    Route::get( 'login-register', 'UsersController@login_register');
});
Route::get('/admin_login', 'auth\LoginController@admin_login_form')->middleware('guest');


Route::get('/confirm', 'Auth\RegisterController@confirm')->middleware('guest');

Route::get('password/confirm', 'Auth\ForgotPasswordController@confirm')->middleware('guest');
Route::post('password/confirm', 'Auth\ForgotPasswordController@check_confirm_code')->middleware('guest');

Route::post('register/active_account', 'Auth\RegisterController@active_account')->name('active.account');

Route::post('register/ajax/resend', 'ApiController@resend')->name('sms.resend');


Route::get('logout',function (){
    Auth::logout();
    return redirect('/');
});

