<?php

require_once __DIR__ . '/../Classe/Model.php';
require_once __DIR__ . '/../Classe/Album.php';
require_once __DIR__ . '/../Classe/Genre.php';
require_once __DIR__ . '/../Classe/Artist.php';

$pdo = require __DIR__ . '/../config/database.php';

$albumModel = new Album($pdo);
$genreModel = new Genre($pdo);
$artistModel = new Artist($pdo);

$genres = $genreModel->getAll();
$artists = $artistModel->getAll();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $releaseYear = filter_input(
        INPUT_POST,
        'release_year',
        FILTER_VALIDATE_INT
    );
    $genreId = filter_input(
        INPUT_POST,
        'genre_id',
        FILTER_VALIDATE_INT
    );
    $artistIds = $_POST['artist_ids'] ?? [];
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors[] = 'Le titre est obligatoire.';
    }

    $currentYear = (int) date('Y');

    if (
        $releaseYear === false
        || $releaseYear < 1900
        || $releaseYear > $currentYear
    ) {
        $errors[] = 'L’année de sortie est invalide.';
    }

    if ($genreId === false || $genreId < 1) {
        $errors[] = 'Veuillez choisir un genre.';
    }

    if (!is_array($artistIds) || count($artistIds) === 0) {
        $errors[] = 'Veuillez choisir au moins un artiste.';
    }

    if (count($errors) === 0) {
        $albumModel->create(
            [
                'genre_id' => $genreId,
                'title' => $title,
                'release_year' => $releaseYear,
                'cover_image' => null,
                'description' => $description,
            ],
            $artistIds
        );

        header('Location: ../index.php?created=1');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Ajouter un album — Rockothèque</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <h1>Rockothèque</h1>

        <nav>
            <a href="../index.php">Accueil</a>
        </nav>
    </header>

    <main>
        <h2>Ajouter un album</h2>

        <?php if (count($errors) > 0): ?>
            <section>
                <h3>Veuillez corriger les erreurs suivantes :</h3>

                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <form method="post">
            <p>
                <label for="title">Titre</label><br>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= htmlspecialchars(
                        $_POST['title'] ?? ''
                    ) ?>"
                    required
                >
            </p>

            <p>
                <label for="release_year">
                    Année de sortie
                </label><br>

                <input
                    type="number"
                    id="release_year"
                    name="release_year"
                    min="1900"
                    max="<?= date('Y') ?>"
                    value="<?= htmlspecialchars(
                        $_POST['release_year'] ?? ''
                    ) ?>"
                    required
                >
            </p>

            <p>
                <label for="genre_id">Genre</label><br>

                <select
                    id="genre_id"
                    name="genre_id"
                    required
                >
                    <option value="">Choisir un genre</option>

                    <?php foreach ($genres as $genre): ?>
                        <option
                            value="<?= $genre['id'] ?>"
                            <?= (
                                ($_POST['genre_id'] ?? '')
                                == $genre['id']
                            ) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($genre['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>

            <fieldset>
                <legend>Artistes</legend>

                <?php foreach ($artists as $artist): ?>
                    <label>
                        <input
                            type="checkbox"
                            name="artist_ids[]"
                            value="<?= $artist['id'] ?>"
                            <?= in_array(
                                (string) $artist['id'],
                                $_POST['artist_ids'] ?? [],
                                true
                            ) ? 'checked' : '' ?>
                        >

                        <?= htmlspecialchars($artist['name']) ?>
                    </label>

                    <br>
                <?php endforeach; ?>
            </fieldset>

            <p>
                <label for="description">Description</label><br>

                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    cols="50"
                ><?= htmlspecialchars(
                    $_POST['description'] ?? ''
                ) ?></textarea>
            </p>

            <button type="submit">Ajouter l’album</button>
        </form>
    </main>
</body>
</html>  