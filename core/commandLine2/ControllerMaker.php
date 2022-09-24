<?php

class ControllerMaker
{

    static function isExist($controllerName)
    {
        $directory = __DIR__ . '/../../app/controllers';
        $controllerName = $controllerName . ".php";
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        if (in_array($controllerName, $scanned_directory)) {
            return true;
        } else {
            return false;
        }
    }

    static function makeController($controllerName, $action)
    {
        $controllerName = ucfirst($controllerName);
        $directory = __DIR__ . '/../../app/controllers/' . $controllerName . ".php";

        if (self::isExist($controllerName)) {

        } else {
            $controllerFileTemplet = fopen("Controller.txt", "r");
            $contents = stream_get_contents($controllerFileTemplet);
           
            $contents = str_replace("{{ControllerName}}", $controllerName, $contents);

            $function = self::createFuction($action);
            $contents = str_replace("{{context}}", $function, $contents);
            $controllerFile = fopen($directory, "w+");

            fwrite($controllerFile, $contents);

        }
    }

    static function createFuction($action)
    {
        $actionFileTemplet = fopen("action.txt", "r");

        $function = stream_get_contents($actionFileTemplet);
        $function = str_replace("{{ActionName}}", $action, $function);
        $colors = new Colors();
        print_r($colors->getColoredString("Would you like to add a render or redirect  \n", "cyan"));
        print_r($colors->getColoredString("[0] =>NO \n", "cyan"));
        print_r($colors->getColoredString("[1] =>render \n", "cyan"));
        print_r($colors->getColoredString("[2] =>redirect \n", "cyan"));
        print_r($colors->getColoredString("=>  ", "cyan"));
        do {
            if (isset($act)) {
                $colors->warning('you should choose between this option');
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
                break ;
            case 1:
                $dir = "../../app/views";


                $result = VieuxMaker::getAllVieux($dir);
                $views=$result["vieux"];
                foreach ($views as $key => $view) {

                    print_r($colors->getColoredString("[" . $key . "] => " . $view . "\n", "cyan"));

                }
                print_r($colors->getColoredString("[NEW] => to create a new view \n", "cyan"));

                $reandVieus = readline("==> ");
                if ($reandVieus >= 0 && $reandVieus <= count($views)) {
                    $function = str_replace("{{Return}}", '$this->render("' . $views[$reandVieus] . '");', $function);

                } else
                { $function = str_replace("{{Return}}", '$this->render();', $function);}
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
