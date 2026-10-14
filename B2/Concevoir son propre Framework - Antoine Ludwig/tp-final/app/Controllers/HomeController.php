<?php

namespace Controllers;

use App\Request;
use App\Response;
use App\View;
use App\Container;

class HomeController
{
    private View $view;

    // Partie à décommenter tant qu'on n'a pas de Container.php
    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../views');
    }

    // Constructeur à décommenter quand on a un Container.php.
    // Commenter le constructeur ci-dessus.
    // public function __construct(Container $container)
    // {
    //     $this->view = $container->get('view');
    // }

    public function index(Request $request): Response
    {
        $contacts = [
            ['id' => 1, 'nom' => 'Rafoul', 'prenom' => 'John', 'mail' => 'john.raful@example.com'],
            ['id' => 2, 'nom' => 'Comprendre MVC', 'prenom' => 'Jane', 'mail' => 'jane.comprendre@example.com'],
            ['id' => 3, 'nom' => 'Les design patterns', 'prenom' => 'Bob', 'mail' => 'bob.design@example.com'],
        ];
        $html = '<h1>Contacts</h1><ul>';
        foreach ($contacts as $a) {
            $html .= "{$a['nom']} {$a['prenom']}</a> - {$a['mail']}</li> <a href=\"/contacts/{$a['id']}/{$a['nom']}/{$a['prenom']}\"> Voir</a><br>";
        }
        $html .= '</ul>';
        return Response::html($html);
    }

}
