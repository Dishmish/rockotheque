<?php

class User extends Model
{
    public function findByUsername(string $username): array|false
    {
        $statement = $this->pdo->prepare(
            'SELECT
                id,
                username,
                email,
                password_hash,
                role
             FROM users
             WHERE username = :username'
        );

        $statement->execute([
            'username' => $username,
        ]);

        return $statement->fetch();
    }

    public function findById(int $id): array|false
    {
        $statement = $this->pdo->prepare(
            'SELECT
                id,
                username,
                email,
                role
             FROM users
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        return $statement->fetch();
    }
    public function create(
    string $username,
    string $email,
    string $password,
    string $role = 'client'
): bool {
    if (!in_array($role, ['client', 'admin'], true)) {
        throw new InvalidArgumentException(
            'Le rôle est invalide.'
        );
    }

    if (strlen($password) < 12 || strlen($password) > 72) {
        throw new InvalidArgumentException(
            'Le mot de passe doit contenir entre 12 et 72 octets.'
        );
    }

    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $statement = $this->pdo->prepare(
        'INSERT INTO users (
            username,
            email,
            password_hash,
            role
         ) VALUES (
            :username,
            :email,
            :password_hash,
            :role
         )'
    );

    return $statement->execute([
        'username' => $username,
        'email' => $email,
        'password_hash' => $passwordHash,
        'role' => $role,
    ]);
}
}