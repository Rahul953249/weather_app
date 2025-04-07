<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default homepage
$routes->get('/', 'WeatherController::index');

// View main weather search UI
$routes->get('/weather', 'WeatherController::index');

// Get weather using city name (used by AJAX)
$routes->get('/get-weather', 'WeatherController::getWeather');

// Save searched weather to database (AJAX POST)
$routes->post('/save-weather', 'WeatherController::saveWeather');

// Optional: if you plan to view logs in a table later
$routes->get('/weather/show', 'WeatherController::show');
