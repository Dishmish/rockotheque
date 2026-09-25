<?php

$configFile = __DIR__ . '/database.local.php';

if (!file_exists($configFile)) {
    exit(
        'Configuration absente. '
        . 'Copiez database.example.php vers database.local.php.'
    );
}

$config = require $configFile;

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $config['host'],
    $config['dbname'],
    $config['charset']
);

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    return new PDO(
        $dsn,
        $config['username'],
        $config['password'],
        $options
    );
} catch (PDOException $exception) {
    exit(
        'Erreur de connexion à la base de données : '
        . $exception->getMessage()
    );
}