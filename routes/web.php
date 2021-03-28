<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/admin', '/admin/ambulatory', 301);
Route::get('/admin/ambulatory', 'admin\AmbulatoryController@index')->name('ambulatory');
Route::get('/admin/hospitalization', 'admin\AmbulatoryController@index')->name('hospitalization');
Route::get('/admin/recovery', 'admin\AmbulatoryController@index')->name('recovery');

Route::get('/admin/specialities', 'admin\SpecialityController@index')->name('specialities.index');
Route::get('/admin/speciality/{speciality}/edit', 'admin\SpecialityController@edit')->name('speciality.edit');
Route::delete('/admin/speciality/{speciality}', 'admin\SpecialityController@destroy')->name('speciality.delete');
Route::post('/admin/speciality', 'admin\SpecialityController@store')->name('speciality.store');
Route::get('/admin/speciality/create', 'admin\SpecialityController@create')->name('speciality.create');
Route::put('/admin/speciality/{speciality}', 'admin\SpecialityController@update')->name('speciality.update');


Route::get('/test', function(){
   $availability = new \App\Availability();
$provider = \App\Models\User::where('id', 9)->first()->toArray();
$provider['services'] = [1];
$provider['settings'] = \App\Models\UserSettings::where('user_id', 9)->first()->toArray();
unset($provider['settings']['user_id']);
$service = \App\Models\MedicalSpeciality::where('id',8)->first()->toArray();
$selected_date="2021-03-30";
dd($availability->get_available_hours($selected_date, $service, $provider));
});

Route::get('/admin/users', 'admin\UserController@index')->name('users.index');
Route::get('/admin/user/{user}/edit', 'admin\UserController@edit')->name('user.edit');
Route::delete('/admin/user/{user}', 'admin\UserController@destroy')->name('user.delete');
Route::post('/admin/user', 'admin\UserController@store')->name('user.store');
Route::get('/admin/user/create', 'admin\UserController@create')->name('user.create');
Route::put('/admin/user/{user}', 'admin\UserController@update')->name('user.update');

Route::get('/admin/login', 'admin\LoginController@showLoginForm')->name('admin.login');
Route::post('/admin/login', 'admin\LoginController@login')->name('admin.login');
Route::get('/admin/logout', 'admin\LoginController@logout')->name('admin.logout');

Route::get('/admin/password/confirm', 'admin\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('/admin/password/confirm', 'admin\ConfirmPasswordController@confirm')->name('password.confirm');

Route::post('/admin/password/email', 'admin\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/admin/password/reset', 'admin\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/admin/password/reset', 'admin\ResetPasswordController@reset')->name('password.update');
Route::post('/admin/password/reset/{token}', 'admin\ResetPasswordController@showResetForm')->name('password.reset');
