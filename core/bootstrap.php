<?php

use Core\App;
use Core\Database;

App::bind('config', require '../config.php');
App::bind('database', new Database(App::get('config')['database']));

function dd($var){
    var_dump($var);
    die();
}
