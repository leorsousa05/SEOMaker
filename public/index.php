<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../src/autoload.php';

use App\Core\Router;
use App\Admin\AuthController;
use App\Admin\DashboardController;
use App\Admin\SettingsController;
use App\Admin\PagesController;
use App\Public\SiteController;

App\Core\Seeder::run();

$router = new Router();

// Public routes
$router->get('/', [SiteController::class, 'home']);
$router->get('/page/{slug}', [SiteController::class, 'page']);
$router->get('/sitemap.xml', [SiteController::class, 'sitemap']);
$router->get('/robots.txt', [SiteController::class, 'robots']);
$router->post('/contact', [SiteController::class, 'contact']);

// Admin routes
$router->get('/admin/login', [AuthController::class, 'login']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->get('/admin/logout', [AuthController::class, 'logout']);
$router->get('/admin', [DashboardController::class, 'index']);
$router->get('/admin/settings', [SettingsController::class, 'index']);
$router->post('/admin/settings', [SettingsController::class, 'index']);
$router->get('/admin/pages', [PagesController::class, 'index']);
$router->get('/admin/pages/edit', [PagesController::class, 'edit']);
$router->get('/admin/pages/edit/{id}', [PagesController::class, 'edit']);
$router->post('/admin/pages/save', [PagesController::class, 'save']);
$router->get('/admin/pages/delete/{id}', [PagesController::class, 'delete']);

// Error handlers
$router->setNotFoundHandler(function () {
    http_response_code(404);
    \App\Core\View::layout('public/layout');
    echo \App\Core\View::render('public/404');
});

$router->dispatch();
