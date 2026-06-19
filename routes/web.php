<?php

use App\Services\Router;

$router = new Router();

// Public Routes
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');
$router->get('/verify', 'CertificateController@index');
$router->post('/verify', 'CertificateController@verify');

return $router;
