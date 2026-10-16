<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Contacts</h1>
  <a class="btn btn-primary" href="/contacts/create">Ajouter un contact</a>
</div>
<table class="table table-striped table-hover align-middle">
  <thead class="table-dark">
    <tr>
      <th scope="col">Nom</th>
      <th scope="col">Prénom</th>
      <th scope="col">Mail</th>
      <th scope="col" colspan="3">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contacts as $a): ?>
      <tr>
        <th scope="row"><?= e($a['nom']) ?></th>
        <td><?= e($a['prenom']) ?></td>
        <td><?= e($a['mail']) ?></td>
        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="/contacts/<?= e((string) $a['id']) ?>">Voir</a></td>
        <td class="text-end">
          <form action="/contacts/<?= e((string) $a['id']) ?>/delete" method="post" class="d-inline">
            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
          </form>
        </td>
        <td class ="text-end"><a class="btn btn-sm btn-outline-primary" href="/contacts/<?= e((string) $a['id']) ?>/edit">Modifier</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
