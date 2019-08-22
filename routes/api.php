<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* fetch all tasks*/
Route::get('/tasks','TaskController@get_all');

/* update tasks */
Route::put('/tasks/{id}/update','TaskController@update_task');

/* mark task as completed */
Route::put('/tasks/{id}/complete','TaskController@complete_task');

/* store a task */
Route::post('/tasks','TaskController@store_task');

/* delete a task */
Route::delete('/tasks/{id}','TaskController@delete_task');