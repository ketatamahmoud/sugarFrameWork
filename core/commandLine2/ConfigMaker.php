<?php

class ConfigMaker
{

    static function isExist()
    {
        $directory = __DIR__.'/../../';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        if (in_array("config.php", $scanned_directory)) {
            return true;
        } else {
            return false;
        }
    }

    static function crateConfigFile()
    {
        $colors = new Colors();


        if (!self::isExist()) {
            echo $colors->getColoredString('____________________________________Create Your DB Config File___________________________________', 'cyan') . "\n";

            $dbName = readline('your database name      :  ');
            $username = readline('username(root)          :  ');
            $password = readline('password (root)         :  ');
            $host = readline('mysql:host (127.0.0.1)  :  ');
            $port = readline('port (3306)             :  ');
            if ($username === '') {
                $username = "root";
            }
            if ($password === "") {
                $password = "root";
            }
            if ($host === "") {
                $host = "127.0.0.1";
            }
            if ($port === "") {
                $port = "3306";
            }
            $confFile = fopen(__DIR__."/../../config.php", "x+");
            $text = "<?php
            
            return [
                'database' => [
                    'name' => '$dbName',
                    'username' => '$username',
                    'password' => '$password',
                    'connection' => 'mysql:host=$host:$port',
                    'options' => [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]
                ]
            ];";
            fwrite($confFile, $text);
            fclose($confFile);
            if(self::isExist()){
                $colors->getColoredStringSuccess("YOUR CONFIGURATION IS OK");
            }
        } else {
            echo $colors->getColoredString('____________________________________reconfigure Your DB Config File___________________________________', 'cyan') . "\n";

            $config = require __DIR__.'/../../config.php';
            $str = explode("=", $config['database']['connection'])[1];
            $oldHost = explode(":", $str)[0];
            $oldPort = explode(":", $str)[1];
            $dbName = readline("your database name(" . $config['database']['name'] . ")   :  ");
            $username = readline('username(' . $config['database']['username'] . ') )          :  ');
            $password = readline('password (' . $config['database']['password'] . ')         :  ');
            $host = readline('mysql:host (' . $oldHost . ')  :  ');
            $port = readline('port (' . $oldPort . ')             :  ');
            if ($dbName === '') {
                $dbName = $config['database']['name'];
            }
            if ($username === '') {
                $username = $config['database']['username'];
            }
            if ($password === "") {
                $password = $config['database']['password'];
            }
            if ($host === "") {
                $host = $oldHost;
            }
            if ($port === "") {
                $port = $oldPort;
            }
            $confFile = fopen(__DIR__."/../../config.php", "w");
            $text = "<?php
            
            return [
                'database' => [
                    'name' => '$dbName',
                    'username' => '$username',
                    'password' => '$password',
                    'connection' => 'mysql:host=$host:$port',
                    'options' => [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]
                ]
            ];";

            fwrite($confFile, $text);
            fclose($confFile);
            if(self::isExist()){
                $colors->getColoredStringSuccess("YOUR RECONFIGURATION IS OK");
            }
        }

    }
}