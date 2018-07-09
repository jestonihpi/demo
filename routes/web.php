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

Auth::routes();



Route::group(['prefix' => 'dashboard',  'middleware' => 'auth'], function()
{
    Route::get('index', 'HomeController@index')->name('home');
    Route::group(['prefix'=>'events'],function(){
        Route::post('save','HomeController@save')->name('save');
        Route::get('view/{id}','HomeController@view')->name('view');
    });
   
    
});


