<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login','AuthController@login');
Route::post('register','AuthController@register');


Route::group(['prefix' => 'category', 'namespace' => 'API','middleware'=>'auth:sanctum'], function () {
    Route::get('list', 'ApiController@categoryList'); //list
    Route::post('create','ApiController@createCategory'); //create
    Route::post('details','ApiController@categoryDetails'); //details
    Route::get('delete/{id}','ApiController@categoryDelete'); //delete
    Route::post('update','ApiController@categoryUpdate'); //update
});

Route::group(['middleware'=>'auth:sanctum'], function () {
    Route::get('logout','AuthController@logout'); //logout
});
