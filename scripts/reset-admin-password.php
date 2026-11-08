<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$pdo = require __DIR__ . '/../config/database.php';

$terminalState = shell_exec('stty -g');

if (!is_string($terminalState) || trim($terminalState) === '') {
    exit("Impossible de masquer la saisie.\n");
}

try {
    system('stty -echo');

    echo "Nouveau mot de passe : ";
    $password = rtrim(fgets(STDIN), "\r\n");
    echo PHP_EOL;

    echo "Confirmer : ";
    $confirmation = rtrim(fgets(STDIN), "\r\n");
    echo PHP_EOL;
} finally {
    system('stty ' . escapeshellarg(trim($terminalState)));
}

if ($password !== $confirmation) {
    exit("Les mots de passe ne correspondent pas.\n");
}

if (strlen($password) < 12 || strlen($password) > 72) {
    exit("Utilisez entre 12 et 72 octets.\n");
}

$statement = $pdo->prepare(
    'UPDATE users
     SET password_hash = :password_hash
     WHERE username = :username AND role = :role'
);

$statement->execute([
    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    'username' => 'admin',
    'role' => 'admin',
]);

echo $statement->rowCount() === 1
    ? "Mot de passe administrateur modifié.\n"
    : "Compte administrateur introuvable.\n";