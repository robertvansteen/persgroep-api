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
    $api->post('authenticate', 'App\Http\Controllers\Security\AuthController@authenticate');
    $api->get('auth/refresh', 'App\Http\Controllers\Security\AuthController@refresh');
	$api->get('me', 'App\Http\Controllers\Security\AuthController@me');

	// Resources
    $api->resource('stories', 'App\Http\Controllers\Resources\StoryController');
    $api->resource('stories.likes', 'App\Http\Controllers\Resources\StoryLikeController');
    $api->delete('stories/{stories}/likes', 'App\Http\Controllers\Resources\StoryLikeController@destroy');

    $api->resource('likes', 'App\Http\Controllers\Resources\LikeController');

	$api->resource('categories', 'App\Http\Controllers\Resources\CategoriesController');
	$api->resource('categories.stories', 'App\Http\Controllers\Resources\CategoryStoryController');
});
