<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ajouter un contact</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4" style="max-width: 560px;">
  <h1 class="h3 mb-4">Ajouter un contact</h1>
  <form action="/contacts" method="post">
    <div class="mb-3">
      <label for="nom" class="form-label">Nom</label>
      <input type="text" class="form-control" name="nom" id="nom" required />
    </div>
    <div class="mb-3">
      <label for="prenom" class="form-label">Prénom</label>
      <input type="text" class="form-control" name="prenom" id="prenom" required />
    </div>
    <div class="mb-3">
      <label for="mail" class="form-label">E-mail</label>
      <input type="email" class="form-control" name="mail" id="mail" required />
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="/" class="btn btn-outline-secondary">Annuler</a>
  </form>
</div>
</body>
</html>
