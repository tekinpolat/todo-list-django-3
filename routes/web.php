<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/', 'TodoController@index');
Route::post('/todo-add', 'TodoController@add');
Route::get('/get-todos', 'TodoController@all');
Route::post('/todo-delete', 'TodoController@delete');
Route::post('/todo-status-change', 'TodoController@statusChange');


#api
Route::get('/api/v1/todo-all', 'Api\V1\TodoController@all');
