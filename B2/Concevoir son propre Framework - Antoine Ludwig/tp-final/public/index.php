<?php
// public/index.php
// Le Front Controller: point d'entrée unique de l'application.
// Toutes les requêtes HTTP passent par ce fichier, sans exception.


require_once __DIR__ . '/../vendor/autoload.php';
$app = new App\Application(dirname(__DIR__));
$router = $app->router();
require __DIR__ . '/../config/routes.php';
$app->run();
