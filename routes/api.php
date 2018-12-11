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


Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('user/details', 'Api\UserController@details');
    Route::post('logout', 'Api\UserController@logout');


    /*-- api role user --*/
    Route::post('role/add', 'Api\RoleController@add');
    Route::post('permission/add', 'Api\PermissionController@add');
    Route::post('roleuser/add', 'Api\UserRoleController@add');
    Route::post('rolepermission/add', 'Api\RolePermissionController@add');
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//
////---- Users
//Route::get('/users','Api\UserController@index');
//
////---- Log Error
//Route::post('/saveLogError','Api\LogController@saveLogError');
