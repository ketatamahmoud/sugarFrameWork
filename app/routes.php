<?php

$router->get('', 'HelloController@hello'); 
$router->get('home ','HomeController@index'); 
$router->get('/home ','HomeController@index');