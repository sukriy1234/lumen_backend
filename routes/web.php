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
$router->get('/', 'OrderController@index');


//USER
$router->post('/login', 'UserController@login');
$router->post('/logout', 'UserController@logout');
$router->get('/user/search',  'UserController@search');


//PRODUCT
$router->post('/product',        ['middleware'=>'auth', 'uses'=>'ProductController@view']);
$router->post('/product/store',  ['middleware'=>'auth', 'uses'=>'ProductController@store']);
$router->post('/product/update', ['middleware'=>'auth', 'uses'=>'ProductController@update']);
$router->post('/product/delete', ['middleware'=>'auth', 'uses'=>'ProductController@delete']);
$router->get('/product/search',  'ProductController@search');


//ORDER
$router->post('/order',        ['middleware'=>'auth', 'uses'=>'OrderController@view']);
$router->post('/order/store',  ['middleware'=>'auth', 'uses'=>'OrderController@store']);
$router->post('/order/update',  ['middleware'=>'auth', 'uses'=>'OrderController@update']);
$router->post('/order/delete',  ['middleware'=>'auth', 'uses'=>'OrderController@delete']);

$router->post('/order/reporter',  ['middleware'=>'auth', 'uses'=>'OrderController@reporter']);
$router->post('/order/update_finance',  ['middleware'=>'auth', 'uses'=>'OrderController@update_finance']);
