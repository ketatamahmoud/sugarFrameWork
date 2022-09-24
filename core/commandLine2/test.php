<?php
require 'ConfigMaker.php';

require 'RouterMaker.php';

require 'ControllerMaker.php';
require 'ModelMaker.php';
require 'VieuxMaker.php';

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
    echo "You also can cal sugar --help to see all option \n";
    print_r($readline = $colors->getColoredString("SUGER CL-> ", "cyan"));
    $action = readline();


    switch ($action) {
        case'config':
            ConfigMaker::crateConfigFile();
            break;
        case 'q':
            exit();
        case "router":
            RouterMaker::makeRoutes();
            break;
        case "model":
            ModelMaker::makeModel();
            break;
        case "vieux":
            VieuxMaker::addVieux();
            break;
        case "controller":
            $controller = readline('==> Controller Name               :  ');
            $action = readline('==> Action                        :  ');
            ControllerMaker::makeController($controller, $action);
            break;

    }
    echo $colors->getColoredString("To Exit press : q ", null, 'red') . "\n \n";

}
