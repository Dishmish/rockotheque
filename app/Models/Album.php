<?php

class Album extends Model
{
    protected string $table = 'albums';

    public function getAll(): array
    {
        $sql = "
            SELECT
                albums.id,
                albums.title,
                albums.release_year,
                albums.cover_image,
                albums.description,
                genres.name AS genre,
                GROUP_CONCAT(
                    DISTINCT artists.name
                    ORDER BY artists.name
                    SEPARATOR ', '
                ) AS artists,
                ROUND(AVG(reviews.rating), 1) AS average_rating
            FROM albums
            JOIN genres
                ON albums.genre_id = genres.id
            JOIN album_artists
                ON albums.id = album_artists.album_id
            JOIN artists
                ON album_artists.artist_id = artists.id
            LEFT JOIN reviews
                ON albums.id = reviews.album_id
            GROUP BY
                albums.id,
                albums.title,
                albums.release_year,
                albums.cover_image,
                albums.description,
                genres.name
            ORDER BY albums.release_year
        ";

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll();
    }
    public function create(array $data, array $artistIds): bool
{
    try {
        $this->pdo->beginTransaction();

        $sql = "
            INSERT INTO albums (
                genre_id,
                title,
                release_year,
                cover_image,
                description
            ) VALUES (
                :genre_id,
                :title,
                :release_year,
                :cover_image,
                :description
            )
        ";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'genre_id' => $data['genre_id'],
            'title' => $data['title'],
            'release_year' => $data['release_year'],
            'cover_image' => $data['cover_image'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $albumId = (int) $this->pdo->lastInsertId();

        $artistStatement = $this->pdo->prepare(
            'INSERT INTO album_artists (album_id, artist_id)
             VALUES (:album_id, :artist_id)'
        );

        $uniqueArtistIds = array_unique(
            array_map('intval', $artistIds)
        );

        foreach ($uniqueArtistIds as $artistId) {
            $artistStatement->execute([
                'album_id' => $albumId,
                'artist_id' => $artistId,
            ]);
        }

        return $this->pdo->commit();
    } catch (Throwable $exception) {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }

        throw $exception;
    }
}
public function find(int $id): array|false
{
    $statement = $this->pdo->prepare(
        'SELECT
            id,
            genre_id,
            title,
            release_year,
            cover_image,
            description
         FROM albums
         WHERE id = :id'
    );

    $statement->execute([
        'id' => $id,
    ]);

    $album = $statement->fetch();

    if ($album === false) {
        return false;
    }

    $artistStatement = $this->pdo->prepare(
        'SELECT artist_id
         FROM album_artists
         WHERE album_id = :album_id'
    );

    $artistStatement->execute([
        'album_id' => $id,
    ]);

    $album['artist_ids'] = array_map(
        'intval',
        $artistStatement->fetchAll(PDO::FETCH_COLUMN)
    );

    return $album;
}
public function update(
    int $id,
    array $data,
    array $artistIds
): bool {
    try {
        $this->pdo->beginTransaction();

        $sql = "
            UPDATE albums
            SET
                genre_id = :genre_id,
                title = :title,
                release_year = :release_year,
                description = :description
            WHERE id = :id
        ";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'genre_id' => $data['genre_id'],
            'title' => $data['title'],
            'release_year' => $data['release_year'],
            'description' => $data['description'] ?? null,
            'id' => $id,
        ]);

        $deleteStatement = $this->pdo->prepare(
            'DELETE FROM album_artists
             WHERE album_id = :album_id'
        );

        $deleteStatement->execute([
            'album_id' => $id,
        ]);

        $artistStatement = $this->pdo->prepare(
            'INSERT INTO album_artists (album_id, artist_id)
             VALUES (:album_id, :artist_id)'
        );

        $uniqueArtistIds = array_unique(
            array_map('intval', $artistIds)
        );

        foreach ($uniqueArtistIds as $artistId) {
            $artistStatement->execute([
                'album_id' => $id,
                'artist_id' => $artistId,
            ]);
        }

        return $this->pdo->commit();
    } catch (Throwable $exception) {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }

        throw $exception;
    }
}
public function delete(int $id): bool
{
    $statement = $this->pdo->prepare(
        'DELETE FROM albums
         WHERE id = :id'
    );

    return $statement->execute([
        'id' => $id,
    ]);
}
}