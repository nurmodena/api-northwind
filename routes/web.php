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

$router->get('/documentation', function () use ($router) {
    $str = <<<EOD
            <h1>Documentation</h1>
            <ul>
                <li><a href="/api/v1/customers"><pre>[GET]/api/v1/customers</pre></a></li>
                <li><a href="/api/v1/customers/ALFKI"><pre>[GET]/api/v1/customers/{customerid}</pre></a></li>
                <li><a href="/api/v1/customers/ALFKI"><pre>[POST]/api/v1/customers</pre></a></li>
            </ul>
            EOD;
    return $str;
});

$router->get('hello[/{name}]', function($name = '') { 
    
    return '<h1>Hello</h1>';
});

function setRoutes($router, $name) {
    $router->group(['prefix' => 'api/v1/' . strtolower($name)], function() use ($router, $name) {
        $router->get('/', $name . "Controller@get");
        $router->post('/', $name . 'Controller@create');
        $router->get('/{id}', $name . 'Controller@getById');
        $router->put('/{id}', $name . 'Controller@update');
        $router->delete('/{id}', $name . 'Controller@delete');
    });
}

$routes = ['Customers', 'Suppliers', 'Categories', 'Employees', 'Orders', 'Employees', 'Products', 'Regions', 'Shippers', 'OrderDetails'];
foreach ($routes as $name) {
    setRoutes($router, $name);
}

$router->group(['prefix' => 'api/v1/account'], function() use ($router) {
    $router->post("/login", 'AuthenticationController@login');
    
});

$router->group(['prefix' => 'api/v1/orders'], function() use ($router) {
    $router->get("/country/{shipCountry}", 'OrdersController@getByShipCountry');
    $router->get("/customers/{customer}", 'OrdersController@getByCustomers');
    $router->get("/orderdate/{startdate}/{enddate}", 'OrdersController@getByOrderDate');
    $router->get("/shipper/{shipper}", 'OrdersController@getByShipper');
});

$router->group(['prefix' => 'api/v1/orderdetails'], function() use ($router) {
    $router->get('/',  "OrderDetailsController@get");
    $router->post("/product/{id}", 'OrderDetailsController@getByProductId');
    
});
