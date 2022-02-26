<?php
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/admin', 'AdminController@index')->name('admin.index');
    create_crud_route('userRole', 'UserRoleController');
    Route::get('userRole/access/{role_id}', 'UserRoleController@access');
    Route::post('userRole/access/{role_id}', 'UserRoleController@add_access');
    create_crud_route('users', 'UsersController', []);
    Route::match(['get', 'post'], 'user/sms/channel', 'UsersController@sms_setting');

    Route::get('users/{id}/additional_info', 'AdditionalInfoController@profile')->name('users.profile');

    Route::get('profile', 'UserPanelController@profile')->name('user.profile');
    Route::get('profile/additional-info', 'UserPanelController@additional_info');

    Route::get('profile/confirm-mobile', 'UserPanelController@confirmMobile');
    Route::post('change-mobile-number', 'UserPanelController@changeMobileNumber');
    Route::post('mobile/update', 'ApiController@update_mobile');
    Route::post('change-mobile-number', 'ApiController@changeMobileNumber');

    Route::post('add/register_detail', 'ApiController@add_register_detail');
    Route::post('bankCard/update', 'ApiController@bankCardUpdate');
    Route::post('email/update', 'ApiController@updateEmail');
    Route::post('date-of-birth/update', 'ApiController@updateDateOfBirth');
    Route::post('nationalIdentityNumber/update', 'ApiController@updateNationalIdentityNumber');
    Route::post('ceremonial_title/update', 'ApiController@updateCeremonialTitle');
    Route::post('idNumber/update', 'ApiController@updateIdNumber');
    Route::post('phone/update', 'ApiController@updatePhone');
    Route::post('reagent/update', 'ApiController@updateReagent');
    Route::post('introduction/update', 'ApiController@updateIntroduction');
    Route::post('introduction/update', 'ApiController@updateIntroduction');
    Route::post('action/update', 'ApiController@updateAction');
    Route::post('description/update', 'ApiController@updateDescription');


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


Route::get('test',function (){
    Auth::logout();
});

