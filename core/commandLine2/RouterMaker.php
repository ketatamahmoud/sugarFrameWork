<?php

class RouterMaker
{

    static function makeRoutes()
    {
        $colors = new Colors();
        echo $colors->getColoredString('____________________________________Config Your Router____________________________________', 'cyan') . "\n";
        do {
            if (isset($method)) {
                $colors->warning('the method must be a "g" for "GET" or "p" for "POST" ');
            }
            $method = readline('==> Method (g=GET\p=POST)     :  ');
        } while ($method !== 'p' && $method !== 'g');
        $uri = readline('==> Uri                       :  ');
        $controller = readline('==> Controller                :  ');
        $action = readline('==> Action                    :  ');

        $controller = ucfirst($controller) . "Controller";

        if ($method == "p")
            $method = "post";
        else
            $method = 'get';

        $routesFile = fopen(__DIR__ . "/../../app/routes.php", "a+");
        $router = " \n" . '$router->' . $method . "('$uri ','$controller" . "@" . "$action')" . ";";
        fwrite($routesFile, $router);
        $colors = new Colors();
        echo "\n";
        echo $colors->getColoredStringSuccess("the routes was successfully create");

        if (!ControllerMaker::isExist($controller))
        {
            $addController = readline('==> Would you like to add a  controller [' . $controller . '] (y=YES\n=NO)     :  ');
            ControllerMaker::makeController($controller,$action);

        }


        fclose($routesFile);


    }
}


