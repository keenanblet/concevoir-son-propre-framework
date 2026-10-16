<?php
// src/Request.php
// Cette classe encapsule la requête HTTP dans un objet.
// Au lieu de manipuler directement $_GET, $_POST, $_SERVER
// (les superglobales PHP), on les range ici une seule fois.
namespace App;

class Request
{
    // La méthode HTTP : GET, POST, PUT, DELETE...
    private string $method;


    private string $uri;

    // Les paramètres d'URL (query string)

    private array $queryParams;

    // Les données du corps de la requête

    private array $bodyParams;


    // Constructeur manuel — utile pour les tests unitaires.

    public function __construct(string $method, string $uri, array $query = [], array $body = [])
    {
        // Normaliser la méthode en majuscules (get → GET)
        $this->method = strtoupper($method);

        // parse_url() extrait uniquement le chemin de l'URI.

        // On retire la query string car elle est déjà dans $query.
        // Si parse_url échoue (URI invalide), on retourne '/' par défaut.
        $this->uri = parse_url($uri, PHP_URL_PATH) ?: '/';

        $this->queryParams = $query;
        $this->bodyParams = $body;
    }

    // Factory method : crée une Request à partir de la vraie requête HTTP.

    public static function fromGlobals(): self
    {
        return new self(
            $_SERVER['REQUEST_METHOD'], 
            $_SERVER['REQUEST_URI'],
            $_GET,
            $_POST
        );
    }

    // Accesseurs : chaque donnée a sa méthode dédiée.
    // On ne donne pas accès aux tableaux bruts : c'est de l'encapsulation.

    // Retourne la méthode HTTP (GET, POST...)
    public function method(): string
    {
        return $this->method;
    }

    // Retourne l'URI nettoyée (/articles/42, sans query string)
    public function uri(): string
    {
        return $this->uri;
    }

    // Récupère un paramètre de la query string ($_GET).

    public function query(string $key, $default = null)
    {
        return $this->queryParams[$key] ?? $default;
    }

    // Récupère un paramètre du corps de la requête ($_POST).
    public function input(string $key, $default = null)
    {
        return $this->bodyParams[$key] ?? $default;
    }
}
