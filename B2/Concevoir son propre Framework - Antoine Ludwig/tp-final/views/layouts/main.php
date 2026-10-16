<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Mon Framework') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

        nav {
            background: #2E74B5;
            padding: 1rem;
        }

        nav a {
            color: white;
            margin-right: 1rem;
            text-decoration: none;
        }

        footer {
            margin-top: 2rem;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 1rem;
        }
    </style>
</head>

<body>
    <nav>
        <a href="/">Accueil</a>
    </nav>
    <main class="container py-4">
        <?= $content ?>
    </main>
    <footer class="container">
        <p>&copy; <?= date('Y') ?> — Liste de contacts</p>
    </footer>
</body>

</html>