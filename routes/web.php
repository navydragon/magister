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
Route::get('/', 'MachineTypeController@index');
Route::get('/calculations', 'CalculationsController@index');
Route::post('/calculations', 'CalculationsController@store');
Route::get('/calculations/create', 'CalculationsController@create');
Route::get('/calculations/{calculation}', 'CalculationsController@show');

Route::post('/calculations/{calculation}/stages', 'CalculationStageController@store');
Route::get('/calculations/{calculation}/predict', 'CalculationsController@predict');

Route::get('/machine_types', 'MachineTypeController@index');
Route::get('/machine_types/create', 'MachineTypeController@create');
Route::post('/machine_types', 'MachineTypeController@store');
Route::get('/machine_types/{machine_type}', 'MachineTypeController@show');


Route::get('/our_machines', 'OurMachineController@index');
Route::post('/our_machines/create', 'OurMachineController@create');
Route::post('/our_machines', 'OurMachineController@store');

Route::get('/drivers', 'DriverController@index');
Route::post('/drivers', 'DriverController@store');

Route::get('/part_types', 'PartTypeController@index');
Route::post('/part_types', 'PartTypeController@store');


Route::get('/parts', 'PartController@index');
Route::post('/parts', 'PartController@store');