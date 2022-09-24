<?php

namespace Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractController
{
    private $twig;

    private function getTwig()
    {
        if ($this->twig == null) {
            $loader = new \Twig\Loader\FilesystemLoader('../app/views');
            $this->twig = new \Twig\Environment($loader);
        }
        return $this->twig;
    }

    /**
     * Render the view.
     *
     * @param string $view
     * @param array $parameters
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $view,array $parameters=[]){
        echo $this->getTwig()->render($view,$parameters);
    }

    /**
     * Redirect to a new page.
     *
     * @param  string $path
     */
    public function redirect($path)
    {
        header("Location: /{$path}");
    }
}
