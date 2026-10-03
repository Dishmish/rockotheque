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
            'created' => isset($_GET['created']),
            'updated' => isset($_GET['updated']),
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
public function create(
    Genre $genreModel,
    Artist $artistModel,
    array $errors = [],
    array $form = []
): void {
    $form = array_merge([
        'title' => '',
        'release_year' => '',
        'genre_id' => '',
        'artist_ids' => [],
        'description' => '',
    ], $form);

    echo $this->twig->render('albums/create.twig', [
        'genres' => $genreModel->getAll(),
        'artists' => $artistModel->getAll(),
        'errors' => $errors,
        'form' => $form,
        'current_year' => (int) date('Y'),
        'csrf_token' => $_SESSION['csrf_token'],
    ]);
}
public function store(
    Genre $genreModel,
    Artist $artistModel
): void {
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token)
        || !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(403);
        echo 'Formulaire invalide. Rechargez la page.';
        return;
    }

    $result = $this->validateAlbumForm(
        $genreModel,
        $artistModel
    );

    if ($result['errors'] !== []) {
        http_response_code(422);

        $this->create(
            $genreModel,
            $artistModel,
            $result['errors'],
            $result['form']
        );

        return;
    }

    try {
        $data = $result['data'];
        $data['cover_image'] = null;

        $created = $this->albumModel->create(
            $data,
            $result['form']['artist_ids']
        );
    } catch (PDOException $exception) {
        error_log((string) $exception);
        $created = false;
    }

    if (!$created) {
        http_response_code(500);

        $this->create(
            $genreModel,
            $artistModel,
            ['Impossible d’ajouter l’album. Veuillez réessayer.'],
            $result['form']
        );

        return;
    }

    header('Location: /albums?created=1', true, 303);
    exit;
}
public function edit(
    int $id,
    Genre $genreModel,
    Artist $artistModel,
    array $errors = [],
    array $form = []
): void {
    $album = $this->albumModel->find($id);

    if ($album === false) {
        http_response_code(404);
        echo 'Album introuvable.';
        return;
    }

    $form = array_merge([
        'title' => $album['title'],
        'release_year' => $album['release_year'],
        'genre_id' => $album['genre_id'],
        'artist_ids' => $album['artist_ids'],
        'description' => $album['description'] ?? '',
    ], $form);

    echo $this->twig->render('albums/edit.twig', [
        'album' => $album,
        'genres' => $genreModel->getAll(),
        'artists' => $artistModel->getAll(),
        'errors' => $errors,
        'form' => $form,
        'current_year' => (int) date('Y'),
        'csrf_token' => $_SESSION['csrf_token'],
    ]);
}
private function validateAlbumForm(
    Genre $genreModel,
    Artist $artistModel
): array {
    $form = [];

    foreach (
        ['title', 'release_year', 'genre_id', 'description']
        as $field
    ) {
        $value = $_POST[$field] ?? '';
        $form[$field] = is_string($value) ? trim($value) : '';
    }

    $errors = [];

    if ($form['title'] === '') {
        $errors[] = 'Le titre est obligatoire.';
    } elseif (mb_strlen($form['title'], 'UTF-8') > 200) {
        $errors[] = 'Le titre ne doit pas dépasser 200 caractères.';
    }

    $year = filter_var(
        $form['release_year'],
        FILTER_VALIDATE_INT
    );

    if (
        $year === false
        || $year < 1900
        || $year > (int) date('Y')
    ) {
        $errors[] = 'L’année de sortie est invalide.';
    }

    $genreId = filter_var(
        $form['genre_id'],
        FILTER_VALIDATE_INT
    );

    $validGenreIds = array_map(
        'intval',
        array_column($genreModel->getAll(), 'id')
    );

    if (
        $genreId === false
        || !in_array($genreId, $validGenreIds, true)
    ) {
        $errors[] = 'Veuillez choisir un genre valide.';
    }

    $validArtistIds = array_map(
        'intval',
        array_column($artistModel->getAll(), 'id')
    );

    $submittedArtistIds = $_POST['artist_ids'] ?? [];
    $form['artist_ids'] = [];

    if (!is_array($submittedArtistIds)) {
        $errors[] = 'La sélection des artistes est invalide.';
    } else {
        foreach ($submittedArtistIds as $value) {
            $artistId = is_string($value)
                ? filter_var($value, FILTER_VALIDATE_INT)
                : false;

            if (
                $artistId === false
                || !in_array($artistId, $validArtistIds, true)
            ) {
                $errors[] = 'Un artiste sélectionné est invalide.';
                continue;
            }

            $form['artist_ids'][] = $artistId;
        }
    }

    $form['artist_ids'] = array_values(
        array_unique($form['artist_ids'])
    );

    if ($form['artist_ids'] === []) {
        $errors[] = 'Veuillez choisir au moins un artiste.';
    }

    if (mb_strlen($form['description'], 'UTF-8') > 5000) {
        $errors[] = 'La description ne doit pas dépasser 5000 caractères.';
    }

    return [
        'form' => $form,
        'errors' => $errors,
        'data' => [
            'genre_id' => $genreId,
            'title' => $form['title'],
            'release_year' => $year,
            'description' => $form['description'],
        ],
    ];
}
public function update(
    int $id,
    Genre $genreModel,
    Artist $artistModel
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

    $result = $this->validateAlbumForm(
        $genreModel,
        $artistModel
    );

    if ($result['errors'] !== []) {
        http_response_code(422);

        $this->edit(
            $id,
            $genreModel,
            $artistModel,
            $result['errors'],
            $result['form']
        );

        return;
    }

    try {
        $updated = $this->albumModel->update(
            $id,
            $result['data'],
            $result['form']['artist_ids']
        );
    } catch (PDOException $exception) {
        error_log((string) $exception);
        $updated = false;
    }

    if (!$updated) {
        http_response_code(500);

        $this->edit(
            $id,
            $genreModel,
            $artistModel,
            ['Impossible de modifier l’album. Veuillez réessayer.'],
            $result['form']
        );

        return;
    }

    header('Location: /albums?updated=1', true, 303);
    exit;
}
}
