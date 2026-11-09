<?php

class AccessLog extends Model
{
    public function record(
        ?int $userId,
        string $username,
        string $ipAddress,
        string $page,
        string $httpMethod
    ): bool {
        $statement = $this->pdo->prepare(
            'INSERT INTO access_logs (
                user_id,
                username,
                ip_address,
                page,
                http_method
             ) VALUES (
                :user_id,
                :username,
                :ip_address,
                :page,
                :http_method
             )'
        );

        return $statement->execute([
            'user_id' => $userId,
            'username' => $username,
            'ip_address' => $ipAddress,
            'page' => $page,
            'http_method' => $httpMethod,
        ]);
    }

    public function getRecent(): array
    {
        $statement = $this->pdo->query(
            'SELECT
                id,
                username,
                ip_address,
                page,
                http_method,
                visited_at
             FROM access_logs
             ORDER BY visited_at DESC, id DESC
             LIMIT 100'
        );

        return $statement->fetchAll();
    }
}