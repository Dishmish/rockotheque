<?php

class Auth
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function currentUser(): array|false
    {
        $id = $_SESSION['user_id'] ?? null;

        if (!is_int($id) || $id < 1) {
            return false;
        }

        $user = $this->userModel->findById($id);

        if ($user === false) {
            unset($_SESSION['user_id']);
        }

        return $user;
    }

    public function requireLogin(): array
    {
        $user = $this->currentUser();

        if ($user === false) {
            header('Location: /login', true, 303);
            exit;
        }

        return $user;
    }

    public function requireAdmin(): array
    {
        $user = $this->requireLogin();

        if ($user['role'] !== 'admin') {
            http_response_code(403);
            exit('Accès réservé à l’administrateur.');
        }

        return $user;
    }
}