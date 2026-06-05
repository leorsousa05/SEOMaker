<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../src/autoload.php';

use App\Core\Router;
use App\Admin\AuthController;
use App\Admin\ContactMessagesController;
use App\Admin\DashboardController;
use App\Admin\RedirectsController;
use App\Admin\SettingsController;
use App\Admin\MediaController;
use App\Admin\PagesController;
use App\Models\Redirect;
use App\Public\SiteController;

App\Core\Seeder::run();

// Check for active redirects before routing
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$redirect = Redirect::findByPath($requestPath);
if ($redirect !== null && $redirect['from_path'] !== $redirect['to_path']) {
    $code = (int) $redirect['type'];
    if ($code !== 301 && $code !== 302) {
        $code = 301;
    }
    http_response_code($code);
    header('Location: ' . $redirect['to_path']);
    exit;
}

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
$router->get('/admin/media', [MediaController::class, 'index']);
$router->post('/admin/media/upload', [MediaController::class, 'upload']);
$router->get('/admin/media/delete/{id}', [MediaController::class, 'delete']);
$router->get('/admin/media/json', [MediaController::class, 'json']);
$router->get('/admin/messages', [ContactMessagesController::class, 'index']);
$router->get('/admin/messages/reply/{id}', [ContactMessagesController::class, 'reply']);
$router->get('/admin/messages/archive/{id}', [ContactMessagesController::class, 'archive']);
$router->get('/admin/messages/delete/{id}', [ContactMessagesController::class, 'delete']);

$router->get('/admin/redirects', [RedirectsController::class, 'index']);
$router->get('/admin/redirects/edit', [RedirectsController::class, 'edit']);
$router->get('/admin/redirects/edit/{id}', [RedirectsController::class, 'edit']);
$router->post('/admin/redirects/save', [RedirectsController::class, 'save']);
$router->get('/admin/redirects/delete/{id}', [RedirectsController::class, 'delete']);

// Error handlers
$router->setNotFoundHandler(function () {
    http_response_code(404);
    \App\Core\View::layout('public/layout');
    echo \App\Core\View::render('public/404');
});

$router->dispatch();
