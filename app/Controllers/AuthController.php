<?php

class AuthController
{
    private User $userModel;
    private \Twig\Environment $twig;

    public function __construct(
        User $userModel,
        \Twig\Environment $twig
    ) {
        $this->userModel = $userModel;
        $this->twig = $twig;
    }

    public function loginForm(
        array $errors = [],
        string $username = ''
    ): void {
        echo $this->twig->render('auth/login.twig', [
            'errors' => $errors,
            'username' => $username,
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }
    public function login(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token)
        || !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(403);
        echo 'Formulaire invalide. Rechargez la page.';
        return;
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $username = is_string($username) ? trim($username) : '';
    $password = is_string($password) ? $password : '';

    if (
        $username === ''
        || mb_strlen($username, 'UTF-8') > 100
        || $password === ''
        || strlen($password) > 72
    ) {
        http_response_code(422);

        $this->loginForm(
            ['Veuillez saisir un nom d’utilisateur et un mot de passe valides.'],
            $username
        );

        return;
    }

    $user = $this->userModel->findByUsername($username);

    if (
        $user === false
        || !password_verify($password, $user['password_hash'])
    ) {
        http_response_code(401);

        $this->loginForm(
            ['Nom d’utilisateur ou mot de passe incorrect.'],
            $username
        );

        return;
    }

    session_regenerate_id(true);

    $_SESSION = [
        'user_id' => (int) $user['id'],
        'csrf_token' => bin2hex(random_bytes(32)),
    ];

    header('Location: /albums', true, 303);
    exit;
}
public function logout(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token)
        || !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(403);
        echo 'Formulaire invalide. Rechargez la page.';
        return;
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();

    header('Location: /login', true, 303);
    exit;
}
}