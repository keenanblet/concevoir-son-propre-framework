<?php
// config/routes.php
// Ce fichier déclare toutes les routes de l'application.


use App\Response;
use App\Request;

use Controllers\ContactController;
use Controllers\HomeController;

/** @var App\Router $router */

$router->get('/contacts/create', [ContactController::class, 'create']);
$router->get('/contacts/{id}', [ContactController::class, 'show']);
$router->get('/', [Controllers\HomeController::class, 'index']);
$router->post('/contacts', [ContactController::class, 'store']);
$router->post('/contacts/{id}/delete', [ContactController::class, 'delete']);
$router->get('/contacts/{id}/edit', [ContactController::class, 'edit']);
$router->post('/contacts/{id}/update', [ContactController::class, 'update']);