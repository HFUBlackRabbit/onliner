<?php

namespace App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private FilesystemLoader $loader;
    private Environment $twig;
    public function __construct()
    {
        $this->loader = new FilesystemLoader(ROOT . '/views');
        $this->twig = new Environment($this->loader);
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }
}