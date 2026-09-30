<?php

class AlbumArtist extends Model
{
    public function getByAlbum(int $albumId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT
                album_artists.artist_id,
                album_artists.role,
                artists.name
             FROM album_artists
             JOIN artists
                ON artists.id = album_artists.artist_id
             WHERE album_artists.album_id = :album_id
             ORDER BY artists.name'
        );

        $statement->execute([
            'album_id' => $albumId,
        ]);

        return $statement->fetchAll();
    }
}
