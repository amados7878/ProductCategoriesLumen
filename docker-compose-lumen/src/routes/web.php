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
    echo 'Hello';
    return $router->app->version();
});
//$router->get('categories',  'CategoryController@index');

/*
$router->group([

 //   'middleware' => 'api',
    'prefix' => 'api'

], function ($router) {

    $router->post('login', ['uses' => 'AuthController@login']);
    $router->post('logout', ['uses' => 'AuthController@logout']);
    $router->post('register', ['uses' => 'AuthController@register']);
    $router->get('me',  ['uses' => 'AuthController@me'] );

});
*/




$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', ['uses' => 'AuthController@login']);
    $router->post('register', ['uses' => 'AuthController@register']);
    $router->get('me', ['uses' => 'AuthController@me']);
    $router->post('logout', ['uses' => 'AuthController@logout']);
});

//$router->apiResource('categories/{category}', 'CategoryController');
//->only('store', 'update', 'destroy');

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('categories',  ['uses' => 'CategoryController@index']);

    $router->get('categories/{id}', ['uses' => 'CategoryController@show']);

    $router->post('categories', ['uses' => 'CategoryController@store']);

    $router->delete('categories/{id}', ['uses' => 'CategoryController@delete']);
    $router->put('categories/{id}', ['uses' => 'CategoryController@update']);
});



//$router->apiResource('products', 'ProductController');

$router->group(['prefix' => 'api'], function () use ($router) {


    $router->post('products/{categoryId}', ['uses' => 'ProductController@store']);

    $router->delete('products/{id}', ['uses' => 'ProductController@delete']);

    $router->put('products/{id}', ['uses' => 'ProductController@update']);
});
