<?php

session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Models/Album.php';
require_once __DIR__ . '/../app/Models/AlbumArtist.php';
require_once __DIR__ . '/../app/Models/Review.php';

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AlbumController.php';

$loader = new \Twig\Loader\FilesystemLoader(
    __DIR__ . '/../app/Views'
);

$twig = new \Twig\Environment($loader, [
    'autoescape' => 'html',
    'strict_variables' => true,
]);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && $path === '/') {
    $controller = new HomeController($twig);
    $controller->index();

} elseif ($method === 'GET' && $path === '/albums') {
    $pdo = require __DIR__ . '/../config/database.php';

    $controller = new AlbumController(
        new Album($pdo),
        $twig
    );

    $controller->index();

} elseif (
    $method === 'GET'
    && preg_match('#^/albums/([1-9][0-9]*)$#', $path, $matches)
) {
    $id = filter_var($matches[1], FILTER_VALIDATE_INT);

    if ($id === false) {
        http_response_code(400);
        exit('Identifiant invalide.');
    }

    $pdo = require __DIR__ . '/../config/database.php';

    $controller = new AlbumController(
        new Album($pdo),
        $twig
    );

    $controller->show(
        $id,
        new AlbumArtist($pdo),
        new Review($pdo)
    );

} elseif (
    $method === 'POST'
    && preg_match(
        '#^/albums/([1-9][0-9]*)/reviews$#',
        $path,
        $matches
    )
) {
    $id = filter_var($matches[1], FILTER_VALIDATE_INT);

    if ($id === false) {
        http_response_code(400);
        exit('Identifiant invalide.');
    }

    $pdo = require __DIR__ . '/../config/database.php';

    $controller = new AlbumController(
        new Album($pdo),
        $twig
    );

    $controller->createReview(
        $id,
        new AlbumArtist($pdo),
        new Review($pdo)
    );

} else {
    http_response_code(404);
    echo 'Page introuvable.';
}