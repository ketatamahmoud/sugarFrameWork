<?php

require '../vendor/autoload.php';
require '../core/bootstrap.php';

use App\Model\Realisation;
use App\Model\Admin;
use Core\{ Router, Request};
var_dump(Realisation::getAll("ASC","delivery_date"));
Router::load('../app/routes.php')
    ->direct(Request::uri(), Request::method());

