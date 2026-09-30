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
    Review $reviewModel
): void {
    $album = $this->albumModel->find($id);

    if ($album === false) {
        http_response_code(404);
        echo 'Album introuvable.';
        return;
    }

    $artists = $albumArtistModel->getByAlbum($id);
    $reviews = $reviewModel->getByAlbum($id);

    echo $this->twig->render('albums/show.twig', [
        'album' => $album,
        'artists' => $artists,
        'reviews' => $reviews,
    ]);
}
}
