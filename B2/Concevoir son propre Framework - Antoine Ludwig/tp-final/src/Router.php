<?php
// src/Router.php
// Le routeur est le cœur du framework, c'est lui qui fait le lien
// entre une URL et le code qui doit s'exécuter.
// Il fait en une seule classe ce que Symfony répartit entre
// le Router, le RouterListener, le ControllerResolver et l'ArgumentResolver.
// Deux responsabilités : enregistrer des routes, puis résoudre une requête.

namespace App;

class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    // ── Enregistrement des routes ────────────────────────────────

    // Méthode générique: enregistre une route pour n'importe quelle
    // méthode HTTP. On normalise en majuscules pour éviter que
    // 'get', 'Get' et 'GET' créent trois entrées différentes.
    public function add(string $method, string $path, callable|array $handler): void
    {
        $method = strtoupper($method);
        $this->routes[$method][$path] = $handler;
    }

    // Raccourci pour GET
    // Au lieu d'écrire $router->add('GET', '/about', ...) à chaque fois,
    // on écrit $router->get('/about', ...).
    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    // Raccourci pour POST
    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }



    public function resolve(Request $request, ?Container $container = null): Response
    {
        // Récupérer les deux infos nécessaires au routage :
        // la méthode HTTP (GET, POST...) et l'URI (/articles/42)
        $method = $request->method();
        $uri    = $request->uri();

        // ── Vérification rapide ──────────────────────────────────
        // Est-ce qu'il existe au moins une route pour cette méthode ?
        // Si personne n'a déclaré de route GET, inutile de chercher.
        if (!isset($this->routes[$method])) {
            return Response::html('<h1>404 — Page non trouvée</h1>', 404);
        }

        // ── Parcourir toutes les routes de cette méthode ─────────
        // On boucle sur chaque route GET (ou POST, etc.) enregistrée.
        // $pattern = le modèle d'URL déclaré ('/articles/{id}')
        // $handler = le code à exécuter (closure ou [Classe, méthode])
        foreach ($this->routes[$method] as $pattern => $handler) {
            $regex = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';

            // ── Tester si l'URI correspond ───────────────────────
            // preg_match() compare l'URI réelle à la regex.
            // Si ça matche, $matches contient les captures :
            //
            //   URI    : /articles/42
            //   Regex  : #^/articles/(?P<id>[^/]+)$#
            //   Result : $matches = [
            //       0    => '/articles/42',   (le match complet)
            //       'id' => '42',             (capture nommée)
            //       1    => '42',             (capture par position)
            //   ]
            //
            // preg_match retourne 1 si ça matche, 0 sinon.

            if (preg_match($regex, $uri, $matches)) {

                // ── Extraire les paramètres nommés ───────────────
                // $matches contient des doublons : les captures sont
                // présentes par nom ('id' => '42') et par position (1 => '42').
                // On ne garde que les clés qui sont des chaînes (les noms).
                //
                // array_filter avec ARRAY_FILTER_USE_KEY filtre par les clés.
                // is_string sur la clé :
                //   'id'  → string → on garde
                //   0     → int    → on jette
                //   1     → int    → on jette
                //
                // Résultat : $params = ['id' => '42']
                // Ou pour /users/alice/posts/7 :
                //   $params = ['userId' => 'alice', 'postId' => '7']

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // ── Appeler le handler avec la Request et les paramètres ──
                // Le handler reçoit maintenant deux arguments :
                //   1. $request → pour accéder aux query params, body, etc.
                //   2. $params  → les paramètres extraits de l'URL
                //
                // Exemple dans la closure :
                //   function (Request $req, array $params) {
                //       $id = $params['id'];  // '42'
                //   }
                if (is_array($handler)) {
                    [$controllerClass, $method] = $handler;
                    $controller = $container ? new $controllerClass($container) : new $controllerClass();
                    $result = $controller->$method($request, $params ?? []);
                } else {
                    $result = $handler($request, $params ?? []);
                }

                // Même logique que la version statique :
                // si le handler retourne déjà une Response, on la prend.
                // Sinon on enveloppe le résultat dans une Response HTML.
                if ($result instanceof Response) {
                    return $result;
                }
                return Response::html((string) $result);
            }
        }

        // Vérifier si l'URL existe pour une autre méthode
        foreach ($this->routes as $registeredMethod => $routes) {
            if ($registeredMethod === $method) continue;
            foreach ($routes as $pattern => $_) {
                $regex = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern);
                if (preg_match('#^' . $regex . '$#', $uri)) {
                    return Response::html(
                        '<h1>405 — Méthode non autorisée</h1>',
                        405
                    );
                }
            }
        }


        // ── Aucune route n'a matché → 404 ────────────────────────
        // On a parcouru toutes les routes de cette méthode HTTP
        // et aucune regex n'a correspondu à l'URI.
        // Le framework retourne une page d'erreur propre.
        return Response::html('<h1>404 — Page non trouvée</h1>', 404);
    }
}
