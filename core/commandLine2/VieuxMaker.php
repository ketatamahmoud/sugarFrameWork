<?php


class VieuxMaker
{

    static function getAllVieux($dir, $newFile = null)
    {
        $files = array_diff(scandir($dir), array('..', '.'));

        $views = [];


        $base = [];

        foreach ($files as $key => $file) {
            if ((strpos($file, '.html.twig') !== false) && ($file !== "base.html.twig")) {
                if ($newFile !== null) {
                    $file = $newFile . "/" . $file;
                }

                array_push($views, $file);


            } elseif (strpos($file, '.html.twig') !== false) {
                if ($newFile !== null) {
                    $file = $newFile . "/" . $file;
                }
                array_push($base, $file);

            } else {


                $res = self::getAllVieux($dir, $file);
                $dir .= "/" . $file;
                $views = array_merge($views, $res["vieux"]);
                array_merge($views, $res["base"]);
            }
        }
        return [
            "vieux" => $views,
            "base" => $base
        ];

    }


    static function addVieux()
    {

        $colors = new Colors();
        $dir = "../../app/views";

        $dir2 = self::getDir($dir, "views");
        $base = self::getAllVieux($dir)["base"];
        $vieuxFileTemplet = fopen("vieux.txt", "r");

        $vieux = stream_get_contents($vieuxFileTemplet);
        print_r($colors->getColoredString(" Choose the Vieux Name  \n", "cyan"));
        $vieuxName = readline("->");


        print_r($colors->getColoredString(" Choose the base  \n", "cyan"));
        foreach ($base as $key => $item) {
            print_r($colors->getColoredString("   [" . $key . "] => $item \n", "cyan"));

        }
        $act = readline("->");
        if (intval($act) >= 0 && intval($act) >= count($base)) {

            $vieux = str_replace("{{base}}", "/" . $dir . $base[intval($act)], $vieux);
        } else
            $vieux = str_replace("{{base}}", "", $vieux);
        $vieuxFile = fopen($dir2 . "/" . $vieuxName . ".html.twig", "w+");
        fwrite($vieuxFile, $vieux);



    }

    static function getDir($dir, $new)
    {
        $colors = new Colors();
        print_r($colors->getColoredString("[0] => $new \n", "cyan"));


        $files = array_diff(scandir($dir), array('..', '.'));
        $directer = [];
        foreach ($files as $key => $file) {

            if (strpos($file, '.html.twig') === false) {
                array_push($directer, $file);
                $last = array_key_last($directer) + 1;
                print_r($colors->getColoredString("   [" . $last . "] => $file \n", "cyan"));
            }

        }
        print_r($colors->getColoredString("   [N] => New director  \n", "cyan"));

        print_r("\n");
        $act = readline("==>");

        if ($act == "0") {
            return $dir;
        } elseif ($act > 0 && $act <= count($directer) + 1) {
            $act = intval($act) - 1;
            $dir = $dir . "/" . $directer[$act];
            self::getDir($dir, $directer[$act]);
        } elseif ($act == "n") {
            print_r($colors->getColoredString(" Name of the new director \n", "cyan"));
            $newDir = readline("==>");
            $dir = $dir . "/" . $newDir;
            mkdir($dir, 0777);
            self::getDir($dir, $newDir);

        }


    }


}