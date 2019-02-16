<?php

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'RedirectController@show');
Route::get('{slug?}', ['uses' => 'RedirectController@show'])->where('slug', '.+');



