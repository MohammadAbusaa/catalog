<?php

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

$router->get('/books',['uses'=>'CatalogController@index','as'=>'home']);
$router->get('/books/search',['uses'=>'CatalogController@show']);
$router->get('/info',['uses'=>'CatalogController@repInfo']);
$router->get('/info/{id}',['uses'=>'CatalogController@info']);
$router->put('/purchase/{id}',['uses'=>'CatalogController@purchase']);
$router->put('/info/update/{id}',['uses'=>'CatalogController@update']);

$router->get('/read',['as'=>'read','uses'=>'CatalogController@read']);

$router->get('/write','CatalogController@write');