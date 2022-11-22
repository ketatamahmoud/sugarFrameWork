<?php


class ModelMaker
{

    public static $pdoSCHEMA;
    public static $pdo;

    static function testConnection()
    {
        $config = require __DIR__."/../../config.php";
        $config = $config["database"];
        if (is_null(self::$pdo)) {
        }
        try {
            self::$pdo = new PDO($config['connection'] . ';dbname=' . $config["name"], $config['username'], $config['password'], $config['options']);
            self::$pdoSCHEMA = new PDO($config['connection'] . ';dbname=INFORMATION_SCHEMA', $config['username'], $config['password'], $config['options']);
        } catch (PDOException $except) {
            echo "Connection failed: " . $except->getMessage();
            die();
        }
        return true;
    }


    static function makeModel()
    {

        $config = require __DIR__."/../../config.php";
        $config = $config["database"];

        if (self::testConnection()) {

            $sql = "SHOW TABLE STATUS FROM " . $config["name"];
            $resultat = self::$pdoSCHEMA->query($sql);
            $tablsName = $resultat->fetchAll(PDO::FETCH_COLUMN);

            self::prepare($tablsName);

            $tabls = [];

            foreach ($tablsName as $tabl) {

                $sql = "SHOW COLUMNS FROM " . $tabl;
                $resultat = self::$pdo->query($sql);


                $columns = $resultat->fetchAll(PDO::FETCH_ASSOC);
                $tabls[$tabl] = $columns;
                foreach ($tabls as $tablName => $tabl) {

                    self::makeAtrubite($tablName, $tabl);
                    self::makeGetSet($tablName, $tabl);
                }


            }
            $sql = "SELECT
  TABLE_NAME,
  COLUMN_NAME,
  REFERENCED_TABLE_NAME,
  REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
  CONSTRAINT_SCHEMA = 'ina' and REFERENCED_COLUMN_NAME is not null ";
            $resultat = self::$pdoSCHEMA->query($sql);
            $tablsss = $resultat->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tablsss as $ref) {
                self::makeReff($ref);
            }



        }
        self::merge($tablsName);

    }


    static function prepare(array $tabelsName)
    {
        $directory = __DIR__ . '../../../app/model';
        if (!is_dir("../../app/model")) {
            mkdir($directory, 0700, true);
        }
        $directory = $directory . "/";

        foreach ($tabelsName as $tabelName) {
            $tabelName = ucfirst($tabelName);
            if (!file_exists("../../app/model/" . $tabelName)) {

                if (!mkdir($concurrentDirectory = $directory . $tabelName, 0700, true) && !is_dir($concurrentDirectory)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                }
            }
            $handle = fopen(__DIR__."/model.txt", "r");
            $contents = stream_get_contents($handle);

            $contents = str_replace("{{ModelName}}", $tabelName, $contents);
            $myfile = fopen($directory . $tabelName . "/" . $tabelName, "w+");
            fwrite($myfile, $contents);
        }
    }


    static function makeAtrubite(string $tabelName, array $tabel)
    {
        $directory = __DIR__ . '../../../app/model/' . ucfirst($tabelName);

        $myfile = fopen($directory . "/" . "atrubite.txt", "w+");
        $contents = "";

        foreach ($tabel as $item) {

            if ($item["Key"] == "PRI")
                $primary = 'protected static $primary =' . "'" . $item["Field"] . "';\n";


            $contents = "";
            $default = "";

            if ($item["Default"] !== Null) {
                $default = " = NULL";

                $default = " = " . $item["Default"];
            }

            if ((strtoupper($item["Type"]) === " INT") or (strtoupper($item["Type"]) == "MEDIUMINT") or (strtoupper($item["Type"]) == "SMALLINT")) {

                $contents = $contents . "protected " . "int " . '$' . $item["Field"] . $default . ";\n";


            } elseif ((strtoupper(strtoupper($item["Type"])) == "DATE") || (strtoupper($item["Type"]) == " TIME") || (strtoupper($item["Type"]) == " DATETIME")) {
                $contents = $contents . "protected " . "DateTime " . '$' . $item["Field"] . ";\n";

            } elseif ((strtoupper($item["Type"]) == "  FLOAT") || (strtoupper($item["Type"]) == "  DOUBLE")) {
                $contents = $contents . "protected " . "float " . '$' . $item["Field"] . $default . "; \n";

            } else {
                $contents = $contents . "protected string " . '$' . $item["Field"] . $default . ";\n";
            }


            fwrite($myfile, $contents);
        }
        fwrite($myfile, $primary);
        fwrite($myfile, 'protected static $object = ' . "'" . $tabelName . "';");


    }


    static function makeGetSet($tablName, $tabl)
    {
        $directory = __DIR__ . '../../../app/model/' . ucfirst($tablName);

        $getFile = fopen(__DIR__."/get.txt", "a+");
        $getTemplet = stream_get_contents($getFile);

        $setFile = fopen(__DIR__."/set.txt", "a+");
        $setTemplet = stream_get_contents($setFile);

        $myfilecontext = fopen($directory . "/" . "context.txt", "w+");
        $get = "";
        foreach ($tabl as $item) {

            if ((strtoupper($item["Type"]) === " INT") or (strtoupper($item["Type"]) == "MEDIUMINT") or (strtoupper($item["Type"]) == "SMALLINT")) {

                $itemType = "int ";


            } elseif ((strtoupper(strtoupper($item["Type"])) == "DATE") || (strtoupper($item["Type"]) == " TIME") || (strtoupper($item["Type"]) == " DATETIME")) {
                $itemType = "DateTime";

            } elseif ((strtoupper($item["Type"]) == "  FLOAT") || (strtoupper($item["Type"]) == "  DOUBLE")) {
                $itemType = "float ";

            } else {
                $itemType = "string ";
            }

            $get = str_replace("{{returnType}}", $itemType, $getTemplet);
            $get = str_replace("{{functionName}}", ucfirst($item['Field']), $get);
            $get = str_replace("{{atrubu}}", $item['Field'], $get);
            $set = str_replace("{{functionName}}", ucfirst($item['Field']), $setTemplet);
            $set = str_replace("{{param}}", $item['Field'], $set);
            $set = str_replace("{{type}}", $itemType, $set);

            fwrite($myfilecontext, $get);
            fwrite($myfilecontext, $set);


        }


    }

    static function makeReff($ref)
    {
        $directory = __DIR__ . '../../../app/model/' . ucfirst($ref["REFERENCED_TABLE_NAME"]);

        $atrubiteFille = fopen($directory . "/" . "atrubite.txt", "a+");
        $getFile = fopen($directory . "/" . "context.txt", "a+");

        $data ="\nprivate  array $".$ref["TABLE_NAME"]."_OBJ =[] ;\n";
        fwrite($atrubiteFille,$data);
        $getFileTemplet = fopen(__DIR__."/superGet.txt", "a+");
        $get = stream_get_contents($getFileTemplet);
        $get=str_replace("{{Model}}",ucfirst($ref["TABLE_NAME"]),$get);
        $get=str_replace("{{functionName}}",ucfirst($ref["TABLE_NAME"])."_OBJ",$get);
        $get=str_replace("{{atrubite}}",$ref["TABLE_NAME"]."_OBJ",$get);
        $get=str_replace("{{ref1}}",$ref["COLUMN_NAME"],$get);
        $get=str_replace("{{ref2}}",$ref["REFERENCED_COLUMN_NAME"],$get);
        fwrite($getFile, $get);

        $directory2 = __DIR__ . '../../../app/model/' . ucfirst($ref["TABLE_NAME"]);

        $atrubiteFille = fopen($directory2 . "/" . "atrubite.txt", "a+");
        $getFile = fopen($directory2 . "/" . "context.txt", "a+");

        $data ="\nprivate  array $".$ref["REFERENCED_TABLE_NAME"]."_OBJ =[]; \n";
        fwrite($atrubiteFille,$data);
        $getFileTemplet = fopen("superGet.txt", "a+");
        $get = stream_get_contents($getFileTemplet);
        $get=str_replace("{{atrubite}}",$ref["REFERENCED_TABLE_NAME"]."_OBJ",$get);
        $get=str_replace("{{functionName}}",ucfirst($ref["REFERENCED_TABLE_NAME"])."_OBJ",$get);
        $get=str_replace("{{Model}}",ucfirst($ref["REFERENCED_TABLE_NAME"]),$get);
        $get=str_replace("{{ref1}}",$ref["REFERENCED_COLUMN_NAME"],$get);
        $get=str_replace("{{ref2}}",$ref["COLUMN_NAME"],$get);
        fwrite($getFile, $get);

    }
    static function merge($tabelsName){
        foreach ($tabelsName as $tabelName){
            $directory = __DIR__ . '../../../app/model/' . ucfirst($tabelName);
            $classFile=fopen($directory . "/" .ucfirst( $tabelName), "r");
            $class = stream_get_contents($classFile);



            $myfilecontext=fopen($directory . "/" . "context.txt", "r");
            $context = stream_get_contents($myfilecontext);

            $atrubiteFile=fopen($directory . "/" . "atrubite.txt", "r");
            $atrubite = stream_get_contents($atrubiteFile);
            $context=$atrubite."\n\n".$context;
            $myClass=str_replace("{{context}}",$context,$class);
            $myClassDir= __DIR__ . '../../../app/model/'. ucfirst($tabelName). ".php";
            $classFile=fopen($myClassDir, "w+");

            fwrite($classFile, $myClass);
            var_dump(PHP_OS);
            if (PHP_OS === 'WINNT')
            {
                exec(sprintf("rd /s /q %s", escapeshellarg($directory)));
            }
            else
            {
                exec(sprintf("rm -rf %s", escapeshellarg('../../app/model/' . ucfirst($tabelName))));
            }
             $colors= new Colors();
            $colors->getColoredStringSuccess("YOUR MODEL ".ucfirst($tabelName)." IS CREATE SUCCESSFUL");


        }


    }
}
