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
Route::get('/bilan/{pays?}', 'BilanController@index');
Route::post('/bilan', 'BilanController@show');
Route::get('autocomplete/{pays?}', 'BilanController@listeEntreprises')->name('autocomplete');
Route::get('/export', 'BilanController@export')->name('export');
Route::post('/import', 'BilanController@import')->name('import');
Route::get('/export_pdf', 'BilanController@export_pdf')->name('export_pdf');
