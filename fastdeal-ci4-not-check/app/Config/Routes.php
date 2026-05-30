<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(false);
$routes->set404Override('App\Controllers\Errors::show404');
$routes->get('/', 'Home::index');
$routes->get('/listings', 'Listings::index');
$routes->get('/listings/(:num)', 'Listings::detail/$1');
$routes->get('/neighborhoods', 'Neighborhoods::index');
$routes->get('/agents', 'Agents::index');
$routes->get('/agents/(:num)', 'Agents::detail/$1');
$routes->get('/blog', 'Blog::index');
$routes->get('/blog/(:segment)', 'Blog::detail/$1');
$routes->get('/about', 'Pages::about');
$routes->get('/contact', 'Pages::contact');

// Lead Submission Route
$routes->post('/leads/submit', 'LeadController::submit');
$routes->post('/contact/submit', 'Pages::contactSubmit');
$routes->post('/newsletter/subscribe', 'Pages::newsletterSubscribe');

// Auth Routes
$routes->get('/login', 'AuthController::login');
$routes->post('/loginAttempt', 'AuthController::loginAttempt');
$routes->get('/logout', 'AuthController::logout');

// Protected Customer Routes
$routes->group('customer', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'CustomerController::dashboard');
    $routes->post('profile/update', 'CustomerController::updateProfile');
});

// Protected Admin Routes
$routes->group('admin', ['filter' => ['auth', 'admin']], static function ($routes) {
    $routes->get('/', 'AdminController::dashboard');

    // Properties
    $routes->get('listings', 'AdminController::listings');
    $routes->get('listings/create', 'AdminController::createProperty');
    $routes->post('listings/store', 'AdminController::storeProperty');
    $routes->get('listings/edit/(:num)', 'AdminController::editProperty/$1');
    $routes->post('listings/update/(:num)', 'AdminController::updateProperty/$1');
    $routes->post('listings/delete/(:num)', 'AdminController::deleteProperty/$1');

    // Agents
    $routes->get('agents', 'AdminController::agents');
    $routes->post('agents/store', 'AdminController::storeAgent');
    $routes->post('agents/delete/(:num)', 'AdminController::deleteAgent/$1');

    // Neighborhoods
    $routes->get('neighborhoods', 'AdminController::neighborhoods');
    $routes->post('neighborhoods/store', 'AdminController::storeNeighborhood');
    $routes->post('neighborhoods/delete/(:num)', 'AdminController::deleteNeighborhood/$1');

    // Blog
    $routes->get('blog', 'AdminController::blog');
    $routes->get('blog/create', 'AdminController::createBlog');
    $routes->post('blog/store', 'AdminController::storeBlog');
    $routes->post('blog/delete/(:num)', 'AdminController::deleteBlog/$1');

    // Leads
    $routes->get('leads', 'AdminController::leads');
    $routes->get('leads/mark/(:num)/(:alpha)', 'AdminController::markLead/$1/$2');
    $routes->get('leads/delete/(:num)', 'AdminController::deleteLead/$1');

    // Settings
    $routes->get('settings', 'AdminController::settings');
    $routes->post('settings/save', 'AdminController::saveSettings');
});
