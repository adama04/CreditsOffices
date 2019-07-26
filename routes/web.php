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

<<<<<<< HEAD
Route::get('/bilan/{pays?}', 'BilanController@index');
Route::post('/bilan', 'BilanController@show');
=======
Route::get('/bilan', 'BilanController@index')->name('billan');
Route::get('autocomplete', 'BilanController@listeEntreprises')->name('autocomplete');




>>>>>>> 5aff8f104a6701d39d626f784f7f9aa16baea9eb
