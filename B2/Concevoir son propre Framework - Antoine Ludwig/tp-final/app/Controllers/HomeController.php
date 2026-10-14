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
        $cheminFichier = __DIR__ . '/../../data/data.json';
        if (file_exists($cheminFichier)) {
            $contacts = json_decode(file_get_contents($cheminFichier), true) ?? [];
        } else {
            $contacts = [];
        }
        $html = '<!DOCTYPE html><html lang="fr"><head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contacts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body><div class="container py-4">';
        $html .= '<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Contacts</h1>
  <a class="btn btn-primary" href="/contacts/create">Ajouter un contact</a>
</div>';
        $html .= '<table class="table table-striped table-hover align-middle"><thead class="table-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Prénom</th>
      <th scope="col">Mail</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>';
        foreach ($contacts as $a) {
            $html .= "<tr>
      <th scope=\"row\">{$a['nom']}</th>
      <td>{$a['prenom']}</td>
      <td>{$a['mail']}</td>
      <td class=\"text-end\"><a class=\"btn btn-sm btn-outline-primary\" href=\"/contacts/{$a['id']}\">Voir</a></td>
    </tr>";
        }
        $html .= '</tbody>';
        $html .= '<tfoot class="table-light">
    <tr>
      <th scope="row" colspan="3">Nombre de contacts</th>
      <td class="text-end"><span class="badge text-bg-secondary">' . count($contacts) . '</span></td>
    </tr>
  </tfoot>
</table>';
        $html .= '</div></body></html>';
        return Response::html($html);
    }

}
