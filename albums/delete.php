<?php

require_once __DIR__ . '/../Classe/Model.php';
require_once __DIR__ . '/../Classe/Album.php';

$pdo = require __DIR__ . '/../config/database.php';

$albumModel = new Album($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Méthode non autorisée.');
}

$id = filter_input(
    INPUT_POST,
    'id',
    FILTER_VALIDATE_INT
);

if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('Identifiant invalide.');
}

$album = $albumModel->find($id);

if ($album === false) {
    http_response_code(404);
    exit('Album introuvable.');
}

$albumModel->delete($id);

header('Location: ../index.php?deleted=1');
exit;