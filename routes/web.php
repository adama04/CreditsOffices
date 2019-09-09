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
Route::get('/bilandf/{pays?}', 'DiffBilanController@index');

Route::post('/bilan', 'BilanController@bilan');
Route::post('/bilandf', 'DiffBilanController@local');

Route::get('/index_import/{pays?}', 'BilanController@index_import')->name('index_import');
Route::get('autocomplete/{pays?}', 'BilanController@listeEntreprises')->name('autocomplete');
Route::post('/export', 'BilanController@export')->name('export');
Route::post('/import/{pays?}', 'BilanController@import')->name('import');
Route::post('/export_pdf', 'BilanController@export_pdf')->name('export_pdf');
