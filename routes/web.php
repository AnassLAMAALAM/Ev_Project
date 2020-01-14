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
    return view('index');
})->name('allEv');


Auth::routes();


$u = new App\User();

if(App\User::admin()){
    Route::get('/type', 'TypesController@create')->name('newType');
}

Route::get('/types', 'EvsController@index')->name('types');
Route::get('/types/{title}', 'EvsController@find')->name('types');


Route::post('/ev/create', 'EvsController@store')->name('createEv');

Route::get('/evs', 'EvsController@evs');

Route::delete('/evs/{id}', 'EvsController@destroy')->name('deleteEv');

Route::post('/type/create', 'TypesController@store')->name('createType');


Route::get('/type/edit/{id}', 'EvsController@edit')->name('editEv');

Route::post('/ev/update/{id}', 'EvsController@update')->name('editEv');



?>