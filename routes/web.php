<?php

$router->post('/login', 'UserController@login');
$router->post('/logout', 'UserController@logout');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/user/search', 'UserController@search');

    //PRODUCT
    $router->group(['prefix' => 'product'], function () use ($router) {
        $router->post('', 'ProductController@view');
        $router->post('store', 'ProductController@store');
        $router->post('update', 'ProductController@update');
        $router->post('delete', 'ProductController@delete');
        $router->get('search', 'ProductController@search');
    });

    //ORDER
    $router->group(['prefix' => 'order'], function () use ($router) {
        $router->post('', 'OrderController@view');
        $router->post('store', 'OrderController@store');
        $router->post('update', 'OrderController@update');
        $router->post('delete', 'OrderController@delete');
        $router->post('reporter', 'OrderController@reporter');
        $router->post('update_finance', 'OrderController@update_finance');
    });
});
