<?php

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

$isHttps = isset($_SERVER['HTTPS'])
    && $_SERVER['HTTPS'] !== ''
    && strtolower((string) $_SERVER['HTTPS']) !== 'off';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Models/Album.php';
require_once __DIR__ . '/../app/Models/AlbumArtist.php';
require_once __DIR__ . '/../app/Models/Review.php';
require_once __DIR__ . '/../app/Models/Genre.php';
require_once __DIR__ . '/../app/Models/Artist.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/AccessLog.php';
require_once __DIR__ . '/../app/Security/Auth.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AlbumController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/AdminController.php';

$loader = new \Twig\Loader\FilesystemLoader(
    __DIR__ . '/../app/Views'
);

$twig = new \Twig\Environment($loader, [
    'autoescape' => 'html',
    'strict_variables' => true,
]);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];
$pdo = require __DIR__ . '/../config/database.php';
$auth = new Auth(new User($pdo));
$currentUser = $auth->currentUser();
$twig->addGlobal('current_user', $currentUser);

$ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';

if (filter_var($ipAddress, FILTER_VALIDATE_IP) === false) {
    $ipAddress = '0.0.0.0';
}

$accessLog = new AccessLog($pdo);

$accessLog->record(
    $currentUser !== false ? (int) $currentUser['id'] : null,
    $currentUser !== false ? $currentUser['username'] : 'Visiteur',
    $ipAddress,
    mb_substr($path, 0, 2048, 'UTF-8'),
    substr($method, 0, 10)
);

$isAdminRoute =
    $path === '/albums/create'
    || $path === '/albums/store'
    || preg_match(
        '#^/albums/[1-9][0-9]*/(edit|update|delete)$#',
        $path
    ) === 1;

if ($isAdminRoute) {
    $auth->requireAdmin();
}

if ($method === 'GET' && $path === '/admin/logs') {
    $controller = new AdminController(
        $accessLog,
        $twig
    );

    $controller->logs($auth);

} elseif ($method === 'POST' && $path === '/logout') {
    $pdo = require __DIR__ . '/../config/database.php';

    $controller = new AuthController(
        new User($pdo),
        $twig
    );

    $controller->logout();

} elseif ($method === 'POST' && $path === '/login') {
    $pdo = require __DIR__ . '/../config/database.php';

    $controller = new AuthController(
        new User($pdo),
        $twig
    );

    $controller->login();

} elseif ($method === 'GET' && $path === '/login') {
    $pdo = require __DIR__ . '/../config/database.php';

    $controller = new AuthController(
        new User($pdo),
        $twig
    );

    $controller->loginForm();

} elseif ($method === 'GET' && $path === '/') {
    $controller = new HomeController($twig);
    $controller->index();

} elseif ($method === 'POST' && $path === '/albums/store') {
    $pdo = require __DIR__ . '/../config/database.php';

    $controller = new AlbumController(
        new Album($pdo),
        $twig
    );

    $controller->store(
        new Genre($pdo),
        new Artist($pdo)
    );

} elseif ($method === 'GET' && $path === '/albums') {
    $pdo = require __DIR__ . '/../config/database.php';

    $controller = new AlbumController(
        new Album($pdo),
        $twig
    );

    $controller->index($auth);

} elseif (
    $method === 'GET'
    && preg_match(
        '#^/albums/([1-9][0-9]*)/edit$#',
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

    $controller->edit(
        $id,
        new Genre($pdo),
        new Artist($pdo)
    );

} elseif (
    $method === 'POST'
    && preg_match(
        '#^/albums/([1-9][0-9]*)/update$#',
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

    $controller->update(
        $id,
        new Genre($pdo),
        new Artist($pdo)
    );

} elseif (
    $method === 'POST'
    && preg_match(
        '#^/albums/([1-9][0-9]*)/delete$#',
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

    $controller->delete($id);

} elseif ($method === 'GET' && $path === '/albums/create') {
    $pdo = require __DIR__ . '/../config/database.php';

    $controller = new AlbumController(
        new Album($pdo),
        $twig
    );

    $controller->create(
        new Genre($pdo),
        new Artist($pdo)
    );

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
    new Review($pdo),
    $auth
);

} else {
    http_response_code(404);
    echo 'Page introuvable.';
}