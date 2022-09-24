<?php

namespace App\Controllers;

use Core\AbstractController;

class HelloController extends AbstractController
{
    /**
     * Show the home page.
     */
    public function hello()
    {
        $this->render('index.html.twig');
    }

}
