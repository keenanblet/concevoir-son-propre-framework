<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($contact) ? 'Modifier' : 'Ajouter' ?> un contact</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4" style="max-width: 560px;">
  <h1 class="h3 mb-4"><?= isset($contact) ? 'Modifier' : 'Ajouter' ?> un contact</h1>
  <form action="<?= isset($contact) ? '/contacts/' . e((string) $contact['id']) . '/update' : '/contacts' ?>" method="post">
    <div class="mb-3">
      <label for="nom" class="form-label">Nom</label>
      <input type="text" class="form-control" name="nom" id="nom" value="<?= e($contact['nom'] ?? '') ?>" required />
    </div>
    <div class="mb-3">
      <label for="prenom" class="form-label">Prénom</label>
      <input type="text" class="form-control" name="prenom" id="prenom" value="<?= e($contact['prenom'] ?? '') ?>" required />
    </div>
    <div class="mb-3">
      <label for="mail" class="form-label">E-mail</label>
      <input type="email" class="form-control" name="mail" id="mail" value="<?= e($contact['mail'] ?? '') ?>" required />
    </div>
    <button type="submit" class="btn btn-primary"><?= isset($contact) ? 'Enregistrer' : 'Ajouter' ?></button>
    <a href="/" class="btn btn-outline-secondary">Annuler</a>
  </form>
</div>
</body>
</html>
