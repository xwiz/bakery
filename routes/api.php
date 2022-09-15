<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(
    ['middleware' => ['cors']],
    function ($api) {
        //AUTH ROUTES
        $api->group(['prefix' => 'auth'], function ($api) {
            $api->post('login', ['as' => 'auth.login', 'uses' => '\App\Http\Controllers\API\LoginController@login']);
            $api->post('otp', ['as' => 'auth.otp', 'uses' => '\App\Http\Controllers\API\LoginController@otpLogin']);
            $api->post('google', ['as' => 'auth.google', 'uses' => '\App\Http\Controllers\API\LoginController@googleLogin']);
            $api->get('logout', ['as' => 'auth.logout', 'uses' => '\App\Http\Controllers\API\LoginController@logout']);
            $api->post('verify', ['as' => 'auth.verify', 'uses' => '\App\Http\Controllers\API\LoginController@verify']);
            $api->get('verify/{id}/{token}', ['as' => 'auth.web.verify', 'uses' => '\App\Http\Controllers\API\LoginController@webVerify']);
            $api->get('auto', ['as' => 'auth.auto', 'uses' => '\App\Http\Controllers\API\LoginController@autoLogin']);
        });

        //USER ROUTES
        $api->group(['prefix' => 'users'], function ($api) {
            $api->post('/', ['as' => 'users.store', 'uses' => '\App\Http\Controllers\API\UserAPIController@store']);
            $api->post('verification', '\App\Http\Controllers\API\UserAPIController@verifyUser');

            $api->group(['middleware' => 'jwt.auth'], function ($api) {
                $api->get('search/{keyword}', ['as' => 'users.search', 'uses' => '\App\Http\Controllers\API\UserAPIController@search']);

                $api->get('lookup/{phone}', ['as' => 'users.lookup', 'uses' => '\App\Http\Controllers\API\UserAPIController@lookup']);
                $api->get('me', ['as' => 'users.me', 'uses' => '\App\Http\Controllers\API\UserAPIController@me']);
                $api->put('/{user}', ['as' => 'users.update', 'uses' => '\App\Http\Controllers\API\UserAPIController@update']);
                $api->post('/{user}/picture', ['as' => 'users.picture', 'uses' => '\App\Http\Controllers\API\UserAPIController@updatePicture']);
                $api->delete('/{user}', ['as' => 'users.destroy', 'uses' => '\App\Http\Controllers\API\UserAPIController@destroy']);
                $api->get('/', ['as' => 'users.index', 'uses' => '\App\Http\Controllers\API\UserAPIController@index']);
                $api->get('/{user}', ['as' => 'users.show', 'uses' => '\App\Http\Controllers\API\UserAPIController@show']);
                $api->post('phone/{phone_number}', '\App\Http\Controllers\API\UserAPIController@addPhoneNumber');
                $api->post('/phone/{verification_token}/verify', '\App\Http\Controllers\API\UserAPIController@verifyPhoneNumber');
            });
        });

        //PROTECTED ROUTES
        $api->group(['middleware' => ['jwt.auth']], function ($api) {
            $api->post('roles/', ['as' => 'roles.store', 'uses' => '\App\Http\Controllers\API\RoleAPIController@store']);
            $api->put('roles/{id}', ['as' => 'roles.update', 'uses' => '\App\Http\Controllers\API\RoleAPIController@update']);
            $api->delete('roles/{id}', ['as' => 'roles.destroy', 'uses' => '\App\Http\Controllers\API\RoleAPIController@destroy']);
        });

        //PASSWORD RESET ROUTES
        $api->group(['prefix' => 'password'], function ($api) {
            $api->post('/forgot', ['as' => 'password.api_forgot', 'uses' => '\App\Http\Controllers\API\PasswordResetAPIController@sendResetLinkEmail']);
            $api->get('/reset', ['as' => 'password.api_reset', 'uses' => '\App\Http\Controllers\API\PasswordResetAPIController@getReset']);
            $api->post('/reset', ['as' => 'password.api_restore', 'uses' => '\App\Http\Controllers\API\PasswordResetAPIController@reset']);
        });

        //RESOURCES
        $api->get('countries/', ['as' => 'countries.index', 'uses' => '\App\Http\Controllers\API\CountryAPIController@index']);
        $api->get('countries/{id}', ['as' => 'countries.show', 'uses' => '\App\Http\Controllers\API\CountryAPIController@show']);

        $api->get('states/', ['as' => 'states.index', 'uses' => '\App\Http\Controllers\API\StateAPIController@index']);
        $api->get('states/{id}', ['as' => 'states.show', 'uses' => '\App\Http\Controllers\API\StateAPIController@show']);

        $api->get('roles/', ['as' => 'roles.index', 'uses' => '\App\Http\Controllers\API\RoleAPIController@index']);
        $api->get('roles/{id}', ['as' => 'roles.show', 'uses' => '\App\Http\Controllers\API\RoleAPIController@show']);


    }
);
