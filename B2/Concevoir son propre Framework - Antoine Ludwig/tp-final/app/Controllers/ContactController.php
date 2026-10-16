<?php

namespace Controllers;

use App\Request;
use App\Response;
use App\View;
use App\Container;

class ContactController
{
    private View $view;

    public function __construct(Container $container)
    {
        $this->view = $container->get('view');
    }
    // ...

    public function show(Request $request, array $params): Response
    {
        $id = (int) $params['id'];
        $contact = file_get_contents(__DIR__ . '/../../data/data.json');
        $contacts = json_decode($contact, true) ?? [];
        $contact = $contacts[$id - 1] ?? null;
        $head = '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<title>Contact</title>'
            . '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">'
            . '</head><body><div class="container py-4" style="max-width: 560px;">';
        $foot = '<a href="/" class="btn btn-outline-secondary mt-3">Retour</a></div></body></html>';

        if (!$contact) {
            return Response::html($head . '<div class="alert alert-warning">Contact non trouvé</div>' . $foot, 404);
        }

        return Response::html($head
            . "<div class=\"card\"><div class=\"card-header\">Contact n°$id</div>"
            . "<div class=\"card-body\"><h5 class=\"card-title\">{$contact['prenom']} {$contact['nom']}</h5>"
            . "<p class=\"card-text mb-0\">{$contact['mail']}</p></div></div>"
            . $foot);
    }

    public function add(Request $request): Response
    {
        $title = $request->input('title', 'Sans titre');
        return Response::json(['created' => true, 'title' => $title], 201);
    }
    public function delete(Request $request, array $params): Response
    {
        $id = (int) $params['id'];
        $cheminFichier = __DIR__ . '/../../data/data.json';
        $contacts = json_decode(file_get_contents($cheminFichier), true) ?? [];
        $contacts = array_values(array_filter($contacts, fn($c) => $c['id'] !== $id));
        file_put_contents($cheminFichier, json_encode($contacts));
        return Response::redirect('/');
    }
    public function create(Request $request): Response
    {
        return Response::html($this->view->renderWithLayout('contacts/create')); 
    }
    public function edit(Request $request, array $params): Response
    {
        $id = (int) $params['id'];
        $contacts = json_decode(file_get_contents(__DIR__ . '/../../data/data.json'), true) ?? [];
        foreach ($contacts as $c) {
            if ($c['id'] === $id) {
                return Response::html($this->view->renderWithLayout('contacts/create', ['contact' => $c]));
            }
        }
        return Response::html('<p>Contact non trouvé</p>', 404);
    }
    public function update(Request $request, array $params): Response
    {
        $id = (int) $params['id'];
        $cheminFichier = __DIR__ . '/../../data/data.json';
        $contacts = json_decode(file_get_contents($cheminFichier), true) ?? [];
        foreach ($contacts as &$c) {
            if ($c['id'] === $id) {
                $c['nom'] = trim((string) $request->input('nom', $c['nom']));
                $c['prenom'] = trim((string) $request->input('prenom', $c['prenom']));
                $c['mail'] = trim((string) $request->input('mail', $c['mail']));
            }
        }
        unset($c);
        file_put_contents($cheminFichier, json_encode($contacts));
        return Response::redirect('/');
    }
    public function store(Request $request): Response
    {
        $nom= trim((string) $request->input('nom', ''));
        $prenom = trim((string) $request->input('prenom', ''));
        $mail = trim((string) $request->input('mail', ''));
        if (empty($nom) || empty($prenom) || empty($mail)) {
            return Response::html('<p>Erreur : tous les champs sont obligatoires.</p>',422);
        }
        $cheminFichier = __DIR__ . '/../../data/data.json';
        $contacts = json_decode(file_get_contents($cheminFichier), true) ?? [];
        $nouveauContact = [
            'id' => count($contacts) + 1,
            'nom' => $nom,
            'prenom' => $prenom,
            'mail' => $mail,
        ];
        $contacts[] = $nouveauContact;
        file_put_contents($cheminFichier, json_encode($contacts));
        return Response::redirect('/');
    }

}
