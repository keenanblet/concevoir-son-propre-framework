<?php

namespace Controllers;

use App\Request;
use App\Response;

class ContactController
{
    public function show(Request $request, array $params): Response
    {
        $id = (int) $params['id'];
        $nom = (string) $params['nom'];
        $prenom = (string) $params['prenom'];
        return Response::html("<h1>Contact n°$id</h1><p>$nom $prenom</p>");
    }

    public function add(Request $request): Response
    {
        $title = $request->input('title', 'Sans titre');
        return Response::json(['created' => true, 'title' => $title], 201);
    }
}
