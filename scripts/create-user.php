<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Models/User.php';

$pdo = require __DIR__ . '/../config/database.php';
$userModel = new User($pdo);

$username = trim(readline("Nom d’utilisateur : "));
$email = trim(readline("Courriel : "));
$role = trim(readline("Rôle (client/admin) : "));

if (
    $username === ''
    || mb_strlen($username, 'UTF-8') > 100
    || strlen($email) > 254
    || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || !in_array($role, ['client', 'admin'], true)
) {
    exit("Informations invalides.\n");
}

if ($userModel->findByUsername($username) !== false) {
    exit("Ce nom d’utilisateur existe déjà.\n");
}

echo "Le mot de passe sera masqué pendant la saisie.\n";

$terminalState = shell_exec('stty -g');

if (!is_string($terminalState) || trim($terminalState) === '') {
    exit("Impossible de masquer la saisie dans ce terminal.\n");
}

try {
    system('stty -echo');

    echo "Mot de passe : ";
    $password = rtrim(fgets(STDIN), "\r\n");
    echo PHP_EOL;

    echo "Confirmer le mot de passe : ";
    $confirmation = rtrim(fgets(STDIN), "\r\n");
    echo PHP_EOL;
} finally {
    system('stty ' . escapeshellarg(trim($terminalState)));
}

if (
    !is_string($password)
    || !is_string($confirmation)
    || $password !== $confirmation
) {
    exit("Les mots de passe ne correspondent pas.\n");
}

try {
    $created = $userModel->create(
        $username,
        $email,
        $password,
        $role
    );

    echo $created
        ? "Utilisateur créé avec succès.\n"
        : "Impossible de créer l’utilisateur.\n";
} catch (InvalidArgumentException $exception) {
    echo $exception->getMessage() . PHP_EOL;
} catch (PDOException $exception) {
    echo "Création impossible. Vérifiez notamment que le courriel est unique.\n";
}