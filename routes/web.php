<?php

use App\Services\Router;

$router = new Router();

// Public Routes
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');
$router->get('/verify', 'CertificateController@index');
$router->post('/verify', 'CertificateController@verify');

// Admin Auth Routes
$router->get('/admin/login', 'Admin\AuthController@loginForm');
$router->post('/admin/login', 'Admin\AuthController@login');
$router->get('/admin/logout', 'Admin\AuthController@logout');

// Protected Admin Routes
$router->get('/admin/dashboard', 'Admin\DashboardController@index', [\App\Middleware\AuthMiddleware::class]);

// Timelines CRUD Routes
$router->get('/admin/timelines', 'Admin\TimelineController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/timelines/create', 'Admin\TimelineController@create', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/timelines/create', 'Admin\TimelineController@store', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/timelines/edit/{id}', 'Admin\TimelineController@edit', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/timelines/edit/{id}', 'Admin\TimelineController@update', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/timelines/delete/{id}', 'Admin\TimelineController@delete', [\App\Middleware\AuthMiddleware::class]);

// Banners CRUD Routes
$router->get('/admin/banners', 'Admin\BannerController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/banners/create', 'Admin\BannerController@create', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/banners/create', 'Admin\BannerController@store', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/banners/edit/{id}', 'Admin\BannerController@edit', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/banners/edit/{id}', 'Admin\BannerController@update', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/banners/delete/{id}', 'Admin\BannerController@delete', [\App\Middleware\AuthMiddleware::class]);

// Projects CRUD Routes
$router->get('/admin/projects', 'Admin\ProjectController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/projects/create', 'Admin\ProjectController@create', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/projects/create', 'Admin\ProjectController@store', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/projects/edit/{id}', 'Admin\ProjectController@edit', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/projects/edit/{id}', 'Admin\ProjectController@update', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/projects/delete/{id}', 'Admin\ProjectController@delete', [\App\Middleware\AuthMiddleware::class]);

return $router;
