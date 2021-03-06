<?php

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

Route::group(['middleware' => 'auth'], function() {
	
	Route::get('/upgrade' , 'userController@listUser');
	Route::get('/downgrade' , 'userController@listUser');

	Route::post('/upgrade/{name}' , 'userController@upgradeUser')->name('upgrade.users');
	Route::post('/downgrade/{name}' , 'userController@upgradeUser')->name('downgrade.users');

});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
