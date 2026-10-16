<?php

namespace Controllers;

use App\Request;
use App\Response;
use App\View;
use App\Container;

class HomeController
{
    private View $view;

    public function __construct(Container $container)
    {
        $this->view = $container->get('view');
    }

    public function index(Request $request): Response
    {
        $cheminFichier = __DIR__ . '/../../data/data.json';
        if (file_exists($cheminFichier)) {
            $contacts = json_decode(file_get_contents($cheminFichier), true) ?? [];
        } else {
            $contacts = [];
        }
        return Response::html($this->view->renderWithLayout('contacts/index', [
            'pageTitle' => 'Contacts',
            'contacts'  => $contacts,
        ]));
    }

}
