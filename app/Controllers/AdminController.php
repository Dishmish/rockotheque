<?php

class AdminController
{
    private AccessLog $accessLogModel;
    private \Twig\Environment $twig;

    public function __construct(
        AccessLog $accessLogModel,
        \Twig\Environment $twig
    ) {
        $this->accessLogModel = $accessLogModel;
        $this->twig = $twig;
    }

    public function logs(Auth $auth): void
    {
        $user = $auth->requireAdmin();

        echo $this->twig->render('admin/logs.twig', [
            'logs' => $this->accessLogModel->getRecent(),
            'username' => $user['username'],
        ]);
    }
}