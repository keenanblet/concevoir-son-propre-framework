<?php
// public/index.php
// Toutes les requêtes HTTP passent par ce fichier, sans exception.
// C'est l'équivalent de public/index.php chez Symfony, mais sans
// le Runtime : on fait les étapes à la main.

require_once __DIR__ . '/../vendor/autoload.php';
$app = new App\Application(dirname(__DIR__));
$router = $app->router();
require __DIR__ . '/../config/routes.php';
$app->run();