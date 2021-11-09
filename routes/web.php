<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ProductController;

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
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/home', 'HomePageController@listProducts');
    $router->post('/login', 'UserController@login');
    $router->post('/register', 'UserController@register');

    $router->post('/search', 'ProductController@search');

    $router->group(['prefix' => 'blog'], function () use ($router) {
        $router->get('/', 'BlogController@index');
        $router->get('/blog-detail/{id}', 'BlogController@detail');
    });

    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('/{id}', 'ProductController@detailProduct');
        $router->get('/shop/{id}', 'ProductController@getProductByType');
        $router->get('/brand/shop', 'ProductController@getProductBrand');
    });


    $router->post('/order', 'OrderController@index');


    $router->group(['prefix' => 'cart'], function () use ($router) {
        $router->get('/detail', 'CartController@index');
        $router->post('/add', 'CartController@add');

    });

});





