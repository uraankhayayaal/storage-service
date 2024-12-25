<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/swagger.yaml', function () {
    return response(file_get_contents('./swagger.yaml'), 200, [
        'Content-Type' => 'application/yaml',
    ]);
});

$router->group(['prefix' => 'api', 'as' => 'storage-service'], function () use ($router) {
    $router->get('/check', 'ApiController@check');
    $router->post('/upload', 'ApiController@upload');
    $router->post('/miltipleupload', 'ApiController@miltipleupload');
    $router->delete('/remove', 'ApiController@remove');
});