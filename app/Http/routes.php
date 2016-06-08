<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    // Authentication
    $api->post('authenticate', 'App\Api\V1\Controllers\AuthenticationController@authenticate');

    $api->resource('stories', 'App\Api\V1\Controllers\StoryController');
    $api->resource('stories.likes', 'App\Api\V1\Controllers\StoryLikeController');
    $api->delete('stories/{stories}/likes', 'App\Api\V1\Controllers\StoryLikeController@destroy');
    $api->resource('likes', 'App\Api\V1\Controllers\LikeController');
});
