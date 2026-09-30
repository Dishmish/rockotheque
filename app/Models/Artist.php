<?php

class Artist extends Model
{
    protected string $table = 'artists';

    public function getAll(): array
    {
        $sql = "
            SELECT
                id,
                name,
                country,
                formation_year,
                biography
            FROM artists
            ORDER BY name
        ";

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll();
    }
}