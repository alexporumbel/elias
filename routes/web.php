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

Route::get('/', 'PublicController@createAmbulatory')->name('homepage');
Route::post('/', 'PublicController@storeAmbulatory')->name('publicAmbulatory.store');
Route::get('/recuperare', 'PublicController@createRecovery')->name('publicRecovery');
Route::post('/recuperare', 'PublicController@storeRecovery')->name('publicRecovery.store');

Route::redirect('/admin', '/admin/ambulatory', 301);

Route::get('/admin/specialities', 'admin\SpecialityController@index')->name('specialities.index');
Route::get('/admin/speciality/{speciality}/edit', 'admin\SpecialityController@edit')->name('speciality.edit');
Route::delete('/admin/speciality/{speciality}', 'admin\SpecialityController@destroy')->name('speciality.delete');
Route::post('/admin/speciality', 'admin\SpecialityController@store')->name('speciality.store');
Route::get('/admin/speciality/create', 'admin\SpecialityController@create')->name('speciality.create');
Route::put('/admin/speciality/{speciality}', 'admin\SpecialityController@update')->name('speciality.update');

Route::get('/admin/ambulatory', 'admin\AmbulatoryController@index')->name('ambulatory');
Route::delete('/admin/ambulatory/{ambulatory}', 'admin\AmbulatoryController@destroy')->name('ambulatory.delete');
Route::post('/admin/ambulatory', 'admin\AmbulatoryController@store')->name('ambulatory.store');
Route::get('/admin/ambulatory/create', 'admin\AmbulatoryController@create')->name('ambulatory.create');

Route::get('/admin/hospitalization', 'admin\HospitalizationController@index')->name('hospitalization');
Route::delete('/admin/hospitalization/{hospitalization}', 'admin\HospitalizationController@destroy')->name('hospitalization.delete');
Route::post('/admin/hospitalization', 'admin\HospitalizationController@store')->name('hospitalization.store');
Route::get('/admin/hospitalization/create', 'admin\HospitalizationController@create')->name('hospitalization.create');

Route::get('/admin/recovery', 'admin\RecoveryController@index')->name('recovery');
Route::delete('/admin/recovery/{recovery}', 'admin\RecoveryController@destroy')->name('recovery.delete');
Route::post('/admin/recovery', 'admin\RecoveryController@store')->name('recovery.store');
Route::get('/admin/recovery/create', 'admin\RecoveryController@create')->name('recovery.create');

Route::get('/admin/recoveryseries', 'admin\RecoverySeriesController@index')->name('recoveryseries.index');
Route::get('/admin/recoveryseries/{recoveryseries}/edit', 'admin\RecoverySeriesController@edit')->name('recoveryseries.edit');
Route::delete('/admin/recoveryseries/{recoveryseries}', 'admin\RecoverySeriesController@destroy')->name('recoveryseries.delete');
Route::post('/admin/recoveryseries', 'admin\RecoverySeriesController@store')->name('recoveryseries.store');
Route::get('/admin/recoveryseries/create', 'admin\RecoverySeriesController@create')->name('recoveryseries.create');
Route::put('/admin/recoveryseries/{recoveryseries}', 'admin\RecoverySeriesController@update')->name('recoveryseries.update');


Route::post('/api/getFirstAppointment', 'ApiController@get_first_appointment');
Route::post('/api/getUnavailDates', 'ApiController@ajax_get_unavailable_dates');
Route::post('/api/getAvailHours', 'ApiController@get_available_hours_ajax');
Route::post('/api/getMedics', 'ApiController@check_speciality_ajax');

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
