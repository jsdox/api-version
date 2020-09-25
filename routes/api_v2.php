<?php

use Illuminate\Support\Facades\Route;

Route::get('welcome', 'V2\UsersController@index');
Route::get('welcome1', 'V1\UsersController@index');
