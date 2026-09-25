<?php

class Genre extends Model
{
    protected string $table = 'genres';

    public function getAll(): array
    {
        $sql = "
            SELECT
                id,
                name,
                description
            FROM genres
            ORDER BY name
        ";

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll();
    }
}