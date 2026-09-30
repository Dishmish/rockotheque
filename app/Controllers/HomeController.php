<?php

class HomeController
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('home/index.twig');
    }
}