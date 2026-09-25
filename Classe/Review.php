<?php

class Review extends Model
{
    protected string $table = 'reviews';

    public function getByAlbum(int $albumId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT
                id,
                album_id,
                reviewer_name,
                rating,
                comment,
                created_at
             FROM reviews
             WHERE album_id = :album_id
             ORDER BY created_at DESC'
        );

        $statement->execute([
            'album_id' => $albumId,
        ]);

        return $statement->fetchAll();
    }

    public function create(
        int $albumId,
        string $reviewerName,
        int $rating,
        ?string $comment
    ): bool {
        if ($rating < 1 || $rating > 5) {
            throw new InvalidArgumentException(
                'La note doit être comprise entre 1 et 5.'
            );
        }

        $statement = $this->pdo->prepare(
            'INSERT INTO reviews (
                album_id,
                reviewer_name,
                rating,
                comment
             ) VALUES (
                :album_id,
                :reviewer_name,
                :rating,
                :comment
             )'
        );

        return $statement->execute([
            'album_id' => $albumId,
            'reviewer_name' => $reviewerName,
            'rating' => $rating,
            'comment' => $comment,
        ]);
    }
}