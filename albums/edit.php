<?php

require_once __DIR__ . '/../Classe/Model.php';
require_once __DIR__ . '/../Classe/Album.php';
require_once __DIR__ . '/../Classe/Genre.php';
require_once __DIR__ . '/../Classe/Artist.php';

$pdo = require __DIR__ . '/../config/database.php';

$albumModel = new Album($pdo);
$genreModel = new Genre($pdo);
$artistModel = new Artist($pdo);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('Identifiant invalide.');
}

$album = $albumModel->find($id);

if ($album === false) {
    http_response_code(404);
    exit('Album introuvable.');
}

$genres = $genreModel->getAll();
$artists = $artistModel->getAll();

$formData = $album;
$selectedArtistIds = $album['artist_ids'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['title'] = trim($_POST['title'] ?? '');
    $formData['release_year'] = filter_input(
        INPUT_POST,
        'release_year',
        FILTER_VALIDATE_INT
    );
    $formData['genre_id'] = filter_input(
        INPUT_POST,
        'genre_id',
        FILTER_VALIDATE_INT
    );
    $formData['description'] = trim(
        $_POST['description'] ?? ''
    );

    $selectedArtistIds = array_map(
        'intval',
        $_POST['artist_ids'] ?? []
    );

    if ($formData['title'] === '') {
        $errors[] = 'Le titre est obligatoire.';
    }

    $currentYear = (int) date('Y');

    if (
        $formData['release_year'] === false
        || $formData['release_year'] < 1900
        || $formData['release_year'] > $currentYear
    ) {
        $errors[] = 'L’année de sortie est invalide.';
    }

    if (
        $formData['genre_id'] === false
        || $formData['genre_id'] < 1
    ) {
        $errors[] = 'Veuillez choisir un genre.';
    }

    if (count($selectedArtistIds) === 0) {
        $errors[] = 'Veuillez choisir au moins un artiste.';
    }

    if (count($errors) === 0) {
        $albumModel->update(
            $id,
            [
                'genre_id' => $formData['genre_id'],
                'title' => $formData['title'],
                'release_year' => $formData['release_year'],
                'description' => $formData['description'],
            ],
            $selectedArtistIds
        );

        header('Location: ../index.php?updated=1');
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
<link rel="stylesheet" href="../css/style.css">
    <title>Modifier un album — Rockothèque</title>
</head>

<body>
    <header>
        <h1>Rockothèque</h1>

        <nav>
            <a href="../index.php">Accueil</a>
        </nav>
    </header>

    <main>
        <h2>Modifier un album</h2>

        <?php if (count($errors) > 0): ?>
            <section>
                <h3>Veuillez corriger les erreurs :</h3>

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
                        (string) $formData['title']
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
                        (string) $formData['release_year']
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
                    <?php foreach ($genres as $genre): ?>
                        <option
                            value="<?= $genre['id'] ?>"
                            <?= (
                                (int) $formData['genre_id']
                                === (int) $genre['id']
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
                                (int) $artist['id'],
                                $selectedArtistIds,
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
                    (string) $formData['description']
                ) ?></textarea>
            </p>

            <button type="submit">
                Enregistrer les modifications
            </button>
        </form>
    </main>
</body>
</html>