<?php
require 'ConfigMaker.php';

require 'RouterMaker.php';

require 'ControllerMaker.php';
require 'ModelMaker.php';
require 'ViewMaker.php';

require 'Colors.php';


$action = '';
$colors = new Colors();
echo $colors->getColoredString(" ▄▄▄▄▄▄▄▄▄▄▄  ▄         ▄  ▄▄▄▄▄▄▄▄▄▄▄  ▄▄▄▄▄▄▄▄▄▄▄  ▄▄▄▄▄▄▄▄▄▄▄ 
▐░░░░░░░░░░░▌▐░▌       ▐░▌▐░░░░░░░░░░░▌▐░░░░░░░░░░░▌▐░░░░░░░░░░░▌
▐░█▀▀▀▀▀▀▀▀▀ ▐░▌       ▐░▌▐░█▀▀▀▀▀▀▀▀▀ ▐░█▀▀▀▀▀▀▀█░▌▐░█▀▀▀▀▀▀▀█░▌
▐░▌          ▐░▌       ▐░▌▐░▌          ▐░▌       ▐░▌▐░▌       ▐░▌
▐░█▄▄▄▄▄▄▄▄▄ ▐░▌       ▐░▌▐░▌ ▄▄▄▄▄▄▄▄ ▐░█▄▄▄▄▄▄▄█░▌▐░█▄▄▄▄▄▄▄█░▌
▐░░░░░░░░░░░▌▐░▌       ▐░▌▐░▌▐░░░░░░░░▌▐░░░░░░░░░░░▌▐░░░░░░░░░░░▌
 ▀▀▀▀▀▀▀▀▀█░▌▐░▌       ▐░▌▐░▌ ▀▀▀▀▀▀█░▌▐░█▀▀▀▀▀▀▀█░▌▐░█▀▀▀▀█░█▀▀ 
          ▐░▌▐░▌       ▐░▌▐░▌       ▐░▌▐░▌       ▐░▌▐░▌     ▐░▌  
 ▄▄▄▄▄▄▄▄▄█░▌▐░█▄▄▄▄▄▄▄█░▌▐░█▄▄▄▄▄▄▄█░▌▐░▌       ▐░▌▐░▌      ▐░▌ 
▐░░░░░░░░░░░▌▐░░░░░░░░░░░▌▐░░░░░░░░░░░▌▐░▌       ▐░▌▐░▌       ▐░▌
 ▀▀▀▀▀▀▀▀▀▀▀  ▀▀▀▀▀▀▀▀▀▀▀  ▀▀▀▀▀▀▀▀▀▀▀  ▀         ▀  ▀         ▀ 
                                                                 ", "light_blue", null) . "\n";
echo "********************************************************************************** \n";
echo "Welcome To " . $colors->getColoredString("SUGAR FW", null, "light_blue") . " (^.^)\n";

while ($action != "q") {
    echo "You can also call sugar --help to see all available options \n";
    print_r($readline = $colors->getColoredString("SUGER CL-> ", "cyan"));
    $action = readline();
    $action = strtolower($action);

    switch ($action) {
        case'config':
            ConfigMaker::crateConfigFile();
            break;
        case 'quit':
            exit();
        case "router":
            RouterMaker::makeRoutes();
            break;
        case "model":
            ModelMaker::makeModel();
            break;
        case "view":
            ViewMaker::addView();
            break;
        case "controller":
            $controller = readline('==> Controller Name               :  ');
            $action = readline('==> Action                        :  ');
            ControllerMaker::makeController($controller, $action);
            break;
        default :
            echo "Invalid Command \n";

    }
    echo $colors->getColoredString("To Exit press : quit ", null, 'red') . "\n \n";

}
