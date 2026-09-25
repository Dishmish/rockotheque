<?php

require_once __DIR__ . '/../Classe/Model.php';
require_once __DIR__ . '/../Classe/Album.php';
require_once __DIR__ . '/../Classe/Review.php';

$pdo = require __DIR__ . '/../config/database.php';

$albumModel = new Album($pdo);
$reviewModel = new Review($pdo);

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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewerName = trim($_POST['reviewer_name'] ?? '');

    $rating = filter_input(
        INPUT_POST,
        'rating',
        FILTER_VALIDATE_INT
    );

    $comment = trim($_POST['comment'] ?? '');

    if ($reviewerName === '') {
        $errors[] = 'Le nom est obligatoire.';
    }

    if (
        $rating === false
        || $rating === null
        || $rating < 1
        || $rating > 5
    ) {
        $errors[] = 'La note doit être comprise entre 1 et 5.';
    }

    if ($comment === '') {
        $errors[] = 'Le commentaire est obligatoire.';
    }

    if (count($errors) === 0) {
        $reviewModel->create(
            $id,
            $reviewerName,
            $rating,
            $comment
        );

        header(
            'Location: show.php?id='
            . $id
            . '&review_created=1'
        );

        exit;
    }
}

$reviews = $reviewModel->getByAlbum($id);

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
    <title>
        <?= htmlspecialchars($album['title']) ?>
        — Rockothèque
    </title>
</head>

<body>
    <header>
        <h1>Rockothèque</h1>

        <nav>
            <a href="../index.php">Accueil</a>
        </nav>
    </header>

    <main>
        <article>
            <h2><?= htmlspecialchars($album['title']) ?></h2>

            <p>
                <strong>Année :</strong>
                <?= htmlspecialchars(
                    (string) $album['release_year']
                ) ?>
            </p>

            <p>
                <?= htmlspecialchars(
                    (string) $album['description']
                ) ?>
            </p>
        </article>

        <section>
            <h2>Avis</h2>
            <?php if (isset($_GET['review_created'])): ?>
    <p>L’avis a été ajouté avec succès.</p>
<?php endif; ?>

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

<h3>Ajouter un avis</h3>

<form method="post">
    <p>
        <label for="reviewer_name">Votre nom</label><br>

        <input
            type="text"
            id="reviewer_name"
            name="reviewer_name"
            value="<?= htmlspecialchars(
                $_POST['reviewer_name'] ?? ''
            ) ?>"
            required
        >
    </p>

    <p>
        <label for="rating">Note</label><br>

        <select id="rating" name="rating" required>
            <option value="">Choisir une note</option>

            <?php for ($note = 5; $note >= 1; $note--): ?>
                <option
                    value="<?= $note ?>"
                    <?= (
                        ($_POST['rating'] ?? '') == $note
                    ) ? 'selected' : '' ?>
                >
                    <?= $note ?>/5
                </option>
            <?php endfor; ?>
        </select>
    </p>

    <p>
        <label for="comment">Commentaire</label><br>

        <textarea
            id="comment"
            name="comment"
            rows="5"
            cols="50"
            required
        ><?= htmlspecialchars(
            $_POST['comment'] ?? ''
        ) ?></textarea>
    </p>

    <button type="submit">
        Publier l’avis
    </button>
</form>

<h3>Avis publiés</h3>

            <?php if (count($reviews) === 0): ?>
                <p>Aucun avis pour cet album.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <article>
                        <h3>
                            <?= htmlspecialchars(
                                $review['reviewer_name']
                            ) ?>
                        </h3>

                        <p>
                            <strong>Note :</strong>
                            <?= (int) $review['rating'] ?>/5
                        </p>

                        <p>
                            <?= htmlspecialchars(
                                (string) $review['comment']
                            ) ?>
                        </p>

                        <p>
                            <small>
                                <?= htmlspecialchars(
                                    $review['created_at']
                                ) ?>
                            </small>
                        </p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>