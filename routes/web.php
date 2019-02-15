<?php

Route::get('/', 'RedirectController@show');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');