<?php

class ControllerMaker
{

    public static function isExist($controllerName): bool
    {
        $directory = __DIR__ . '/../../app/controllers';
        $controllerName .= "Controller.php";
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        if (in_array($controllerName, $scanned_directory, true)) {
            return true;
        }

        return false;
    }

    static function makeController($controllerName, $action)
    {
        $colors = new Colors();
        $controllerName = ucfirst($controllerName).'Controller';
        $directory = __DIR__ . '/../../app/controllers/' . $controllerName . ".php";
        if (self::isExist($controllerName) === true) {
            echo $colors->getColoredString("Controller already exists!  \n", "red");
        } else {
            $controllerFileTemplate = fopen(__DIR__ . "/controller.txt", "r");
            $contents = stream_get_contents($controllerFileTemplate);
            $contents = str_replace("{{ControllerName}}", $controllerName, $contents);
            $function = self::createFuction($action);
            $contents = str_replace("{{context}}", $function, $contents);
            $controllerFile = fopen($directory, "w+");
            fwrite($controllerFile, $contents);
            $colors->getColoredStringSuccess("Controller created successfully!");
        }
    }

    static function createFuction($action)
    {
        $actionFileTemplate = fopen(__DIR__ . "/action.txt", "r");
        $function = stream_get_contents($actionFileTemplate);
        $function = str_replace("{{ActionName}}", $action, $function);
        $colors = new Colors();
        print_r($colors->getColoredString("Would you like to add a render or redirect methods? \n", "cyan"));
        print_r($colors->getColoredString("[0] =>NO \n", "cyan"));
        print_r($colors->getColoredString("[1] =>render \n", "cyan"));
        print_r($colors->getColoredString("[2] =>redirect \n", "cyan"));
        print_r($colors->getColoredString("=>  ", "cyan"));
        do {
            if (isset($act)) {
                $colors->warning('You should choose between those options.');
                print_r($colors->getColoredString("[0] =>NO \n", "cyan"));
                print_r($colors->getColoredString("[1] =>render \n", "cyan"));
                print_r($colors->getColoredString("[2] =>redirect \n", "cyan"));
                print_r($colors->getColoredString("=>  ", "cyan"));
            }
            $act = readline();
        } while ($act !== '0' && $act !== '1' && $act !== '2');
        switch ($act) {
            case 0:
                $function = str_replace("{{Return}}", '', $function);
                break;
            case 1:
                $dir = __DIR__ . "/../../app/views/";
                $result = ViewMaker::getAllViews($dir);
                $views = $result["view"];
                foreach ($views as $key => $view) {

                    print_r($colors->getColoredString("[" . $key . "] => " . $view . "\n", "cyan"));

                }
                print_r($colors->getColoredString("[NEW] => to create a new view \n", "cyan"));

                $readView = readline("==> ");
                if ($readView >= 0 && $readView <= count($views)) {
                    $function = str_replace("{{Return}}", '$this->render("' . $views[$readView] . '");', $function);

                } else {
                    var_dump('new');
                    $viewMaker = new ViewMaker();
                    $viewName = $viewMaker::addView();
                    $function = str_replace("{{Return}}", '$this->render("'.$viewName.".html.twig".'");', $function);
                }
                break;

            case 2:
                $directory = __DIR__ . "/../../app/routes.php";

                $file = fopen($directory, "r");
                $routes = [];

                foreach (file($directory) as $line) {
                    if (strpos($line, "@") !== false) {
                        $res = substr($line, strpos($line, "@") + 1);
                        $res = substr($res, 0, strpos($res, "'"));
                        array_push($routes, $res);
                    }

                }
                foreach ($routes as $key => $route) {
                    print_r($colors->getColoredString("[" . $key . "] => " . $route . "\n", "cyan"));

                }
                $redirect = readline("==> ");
                if ($redirect >= 0 && $redirect <= count($routes)) {
                    $function = str_replace("{{Return}}", '$this->redirect(\'' . $routes[$redirect] . '\');', $function);
                } else {
                    $function = str_replace("{{Return}}", '$this->redirect();', $function);
                }


                fclose($file);


        }


        return $function;
    }


}
