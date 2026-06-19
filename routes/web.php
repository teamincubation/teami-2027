<?php

use App\Services\Router;

$router = new Router();

// Public Routes
$router->get('/', 'HomeController@index');
$router->get('/setup-env', 'HomeController@setup');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');
$router->get('/verify', 'CertificateController@index');
$router->post('/verify', 'CertificateController@verify');

// Admin Auth Routes
$router->get('/auth/admin-login', 'Admin\AuthController@loginForm');
$router->post('/auth/admin-login', 'Admin\AuthController@login');
$router->get('/auth/admin-logout', 'Admin\AuthController@logout');
$router->get('/auth/google', 'Admin\AuthController@googleRedirect');
$router->get('/auth/google/callback', 'Admin\AuthController@googleCallback');
$router->get('/auth/forgot-password', 'Admin\AuthController@forgotPasswordForm');
$router->post('/auth/forgot-password', 'Admin\AuthController@sendResetLink');
$router->get('/reset-password', 'Admin\AuthController@resetPasswordForm');
$router->post('/reset-password', 'Admin\AuthController@updatePassword');

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

// Events CRUD Routes
$router->get('/admin/events', 'Admin\EventController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/events/create', 'Admin\EventController@create', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/events/create', 'Admin\EventController@store', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/events/edit/{id}', 'Admin\EventController@edit', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/events/edit/{id}', 'Admin\EventController@update', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/events/delete/{id}', 'Admin\EventController@delete', [\App\Middleware\AuthMiddleware::class]);

// Campaigns CRUD Routes
$router->get('/admin/campaigns', 'Admin\CampaignController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/campaigns/create', 'Admin\CampaignController@create', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/campaigns/create', 'Admin\CampaignController@store', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/campaigns/edit/{id}', 'Admin\CampaignController@edit', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/campaigns/edit/{id}', 'Admin\CampaignController@update', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/campaigns/delete/{id}', 'Admin\CampaignController@delete', [\App\Middleware\AuthMiddleware::class]);

// Partners CRUD Routes
$router->get('/admin/partners', 'Admin\PartnerController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/partners/create', 'Admin\PartnerController@create', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/partners/create', 'Admin\PartnerController@store', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/partners/edit/{id}', 'Admin\PartnerController@edit', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/partners/edit/{id}', 'Admin\PartnerController@update', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/partners/delete/{id}', 'Admin\PartnerController@delete', [\App\Middleware\AuthMiddleware::class]);

// Form Builder Routes
$router->get('/admin/forms', 'Admin\FormController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/forms/event/{id}', 'Admin\FormController@eventQuestions', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/forms/event/{id}', 'Admin\FormController@saveEventQuestions', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/forms/internship/{id}', 'Admin\FormController@internshipQuestions', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/forms/internship/{id}', 'Admin\FormController@saveInternshipQuestions', [\App\Middleware\AuthMiddleware::class]);

// Incubants Portal Routes
$router->get('/admin/incubants', 'Admin\IncubantController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/incubants/view-internship/{id}', 'Admin\IncubantController@viewInternship', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/incubants/view-volunteer/{id}', 'Admin\IncubantController@viewVolunteer', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/incubants/status-internship/{id}', 'Admin\IncubantController@statusInternship', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/incubants/status-volunteer/{id}', 'Admin\IncubantController@statusVolunteer', [\App\Middleware\AuthMiddleware::class]);

// Certificates CRUD Routes
$router->get('/admin/certificates', 'Admin\CertificateAdminController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/certificates/create', 'Admin\CertificateAdminController@create', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/certificates/create', 'Admin\CertificateAdminController@store', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/certificates/edit/{id}', 'Admin\CertificateAdminController@edit', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/certificates/edit/{id}', 'Admin\CertificateAdminController@update', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/certificates/delete/{id}', 'Admin\CertificateAdminController@delete', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/certificates/import', 'Admin\CertificateAdminController@importForm', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/certificates/import', 'Admin\CertificateAdminController@import', [\App\Middleware\AuthMiddleware::class]);

// Enquiries Routes
$router->get('/admin/enquiries', 'Admin\EnquiryController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin/enquiries/view/{id}', 'Admin\EnquiryController@view', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/enquiries/reply/{id}', 'Admin\EnquiryController@reply', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/enquiries/delete/{id}', 'Admin\EnquiryController@delete', [\App\Middleware\AuthMiddleware::class]);

// Subscribers Routes
$router->get('/admin/subscribers', 'Admin\SubscriberController@index', [\App\Middleware\AuthMiddleware::class]);
$router->post('/admin/subscribers/delete/{id}', 'Admin\SubscriberController@delete', [\App\Middleware\AuthMiddleware::class]);

// Audit Logs Routes
$router->get('/admin/audit-logs', 'Admin\AuditLogController@index', [\App\Middleware\AuthMiddleware::class]);

return $router;
