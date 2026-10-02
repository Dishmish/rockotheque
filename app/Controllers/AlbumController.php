<?php

class AlbumController
{
    private Album $albumModel;
    private \Twig\Environment $twig;

    public function __construct(
        Album $albumModel,
        \Twig\Environment $twig
    ) {
        $this->albumModel = $albumModel;
        $this->twig = $twig;
    }

    public function index(): void
    {
        $albums = $this->albumModel->getAll();

        echo $this->twig->render('albums/index.twig', [
            'albums' => $albums,
        ]);
    }
    public function show(
    int $id,
    AlbumArtist $albumArtistModel,
    Review $reviewModel,
    array $errors = [],
    array $form = []
): void {
    $album = $this->albumModel->find($id);

    if ($album === false) {
        http_response_code(404);
        echo 'Album introuvable.';
        return;
    }

    $artists = $albumArtistModel->getByAlbum($id);
    $reviews = $reviewModel->getByAlbum($id);

    $form = array_merge([
        'reviewer_name' => '',
        'rating' => '',
        'comment' => '',
    ], $form);

    echo $this->twig->render('albums/show.twig', [
        'album' => $album,
        'artists' => $artists,
        'reviews' => $reviews,
        'errors' => $errors,
        'form' => $form,
        'csrf_token' => $_SESSION['csrf_token'],
        'review_created' => isset($_GET['review_created'])
            && $errors === [],
    ]);
}
public function createReview(
    int $id,
    AlbumArtist $albumArtistModel,
    Review $reviewModel
): void {
    if ($this->albumModel->find($id) === false) {
        http_response_code(404);
        echo 'Album introuvable.';
        return;
    }

    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token)
        || !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(403);
        echo 'Formulaire invalide. Rechargez la page.';
        return;
    }

    $form = [];

    foreach (['reviewer_name', 'rating', 'comment'] as $field) {
        $value = $_POST[$field] ?? '';
        $form[$field] = is_string($value) ? trim($value) : '';
    }

    $errors = [];

    if ($form['reviewer_name'] === '') {
        $errors[] = 'Le nom est obligatoire.';
    } elseif (
        mb_strlen($form['reviewer_name'], 'UTF-8') > 100
    ) {
        $errors[] = 'Le nom ne doit pas dépasser 100 caractères.';
    }

    $rating = filter_var(
        $form['rating'],
        FILTER_VALIDATE_INT
    );

    if ($rating === false || $rating < 1 || $rating > 5) {
        $errors[] = 'La note doit être comprise entre 1 et 5.';
    }

    if ($form['comment'] === '') {
        $errors[] = 'Le commentaire est obligatoire.';
    } elseif (mb_strlen($form['comment'], 'UTF-8') > 5000) {
        $errors[] = 'Le commentaire ne doit pas dépasser 5000 caractères.';
    }

    if ($errors !== []) {
        http_response_code(422);

        $this->show(
            $id,
            $albumArtistModel,
            $reviewModel,
            $errors,
            $form
        );

        return;
    }

    try {
        $created = $reviewModel->create(
            $id,
            $form['reviewer_name'],
            $rating,
            $form['comment']
        );
    } catch (PDOException $exception) {
        error_log((string) $exception);
        $created = false;
    }

    if (!$created) {
        http_response_code(500);

        $this->show(
            $id,
            $albumArtistModel,
            $reviewModel,
            ['Impossible d’ajouter l’avis. Veuillez réessayer.'],
            $form
        );

        return;
    }

    header(
        'Location: /albums/' . $id . '?review_created=1',
        true,
        303
    );

    exit;
}
}
