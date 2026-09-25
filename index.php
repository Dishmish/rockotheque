<?php

require_once __DIR__ . '/Classe/Model.php';
require_once __DIR__ . '/Classe/Album.php';

$pdo = require __DIR__ . '/config/database.php';

$albumModel = new Album($pdo);
$albums = $albumModel->getAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
          <link rel="stylesheet" href="css/style.css">

    <title>Rockothèque</title>
</head>

<body>
    <h1>Rockothèque</h1>

    <p>Ma collection personnelle d’albums rock.</p>
    <?php if (isset($_GET['created'])): ?>
    <p>L’album a été ajouté avec succès.</p>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <p>L’album a été modifié avec succès.</p>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <p>L’album a été supprimé avec succès.</p>
<?php endif; ?>

<p>
    <a href="albums/create.php">
        Ajouter un album
    </a>
</p>

    <h2>Albums</h2>

    <?php foreach ($albums as $album): ?>
        <article>
            <h3>
                <?= htmlspecialchars($album['title']) ?>
            </h3>

            <p>
                <strong>Artiste :</strong>
                <?= htmlspecialchars($album['artists']) ?>
            </p>

            <p>
                <strong>Genre :</strong>
                <?= htmlspecialchars($album['genre']) ?>
            </p>

            <p>
                <strong>Année :</strong>
                <?= htmlspecialchars(
                    (string) $album['release_year']
                ) ?>
            </p>

            <p>
                <strong>Note moyenne :</strong>

                <?php if ($album['average_rating'] !== null): ?>
                    <?= htmlspecialchars(
                        (string) $album['average_rating']
                    ) ?>/5
                <?php else: ?>
                    Aucune évaluation
                <?php endif; ?>
            </p>

           <p>
    <?= htmlspecialchars($album['description']) ?>
</p>

<p>
    <a href="albums/show.php?id=<?= (int) $album['id'] ?>">
        Voir les avis
    </a>
</p>

<p>
    <a href="albums/edit.php?id=<?= (int) $album['id'] ?>">
        Modifier
    </a>
</p>

<form
    action="albums/delete.php"
    method="post"
    onsubmit="return confirm(
        'Voulez-vous vraiment supprimer cet album ?'
    );"
>
    <input
        type="hidden"
        name="id"
        value="<?= (int) $album['id'] ?>"
    >

    <button type="submit">
        Supprimer
    </button>
</form>

</article>
<?php endforeach; ?>