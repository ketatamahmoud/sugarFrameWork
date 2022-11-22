<?php


class ViewMaker
{

    public static function getAllViews($dir, $newFile = null): array
    {
        $files = array_diff(scandir($dir), array('..', '.'));
        $views = [];
        $base = [];
        foreach ($files as $key => $file) {
            if ((str_contains($file, '.html.twig')) && ($file !== "base.html.twig")) {
                if ($newFile !== null) {
                    $file = $newFile . "/" . $file;
                }
                $views[] = $file;
            } elseif (str_contains($file, '.html.twig')) {
                if ($newFile !== null) {
                    $file = $newFile . "/" . $file;
                }
                $base[] = $file;
            } else {
                $dir .= "/" . $file;
                $res = self::getAllViews($dir, $file);
                $views = array_merge($views, $res["view"]);
            }
        }
        return [
            "view" => $views,
            "base" => $base
        ];
    }


    static function addView()
    {

        $colors = new Colors();
        $dir = __DIR__. "/../../app/views";
        $dir2 = self::getDir($dir, "views");
        var_dump("azertyuio");
        var_dump($dir2);
        var_dump("azertyuio");
        $base = self::getAllViews($dir)["base"];
        $viewFileTemplate = fopen(__DIR__."/view.txt", "r");

        $view = stream_get_contents($viewFileTemplate);
        print_r($colors->getColoredString(" Choose the View Name  \n", "cyan"));
        $viewName = readline("->");


        //print_r($colors->getColoredString(" Choose the base  \n", "cyan"));
        /*foreach ($base as $key => $item) {
            print_r($colors->getColoredString("   [" . $key . "] => $item \n", "cyan"));

        }
        $act = readline("->");
        if ((int)$act >= 0 && (int)$act <= count($base)) {
            //$view = str_replace("{{base}}", "'" . $dir . $base[(int)$act]."'", $view);
            $view = str_replace("{{base}}", "", $view);
        } else {
            //$view = str_replace("{{base}}", "", $view);
        }*/
        $view = str_replace("{{base}}", "''", $view);
        var_dump($dir2.'/'.$viewName.".html.twig");
        $viewFile = fopen($dir2 . "/" . $viewName . ".html.twig", 'w');
        fwrite($viewFile, $view);
        return $viewName;
    }

    public static function getDir($dir, $new = null)
    {
        $colors = new Colors();
        print_r($colors->getColoredString("[0] => $new \n", "cyan"));
        $files = array_diff(scandir($dir), array('..', '.'));
        $directory = [];
        foreach ($files as $key => $file) {

            if (!str_contains($file, '.html.twig')) {
                $directory[] = $file;
                $last = array_key_last($directory) + 1;
                print_r($colors->getColoredString("   [" . $last . "] => $file \n", "cyan"));
            }
            /*{
                $dir .= "/" . $file;
                $res = self::getDir($dir);
                $directory = array_merge($directory, $res["view"]);
            }*/

        }
        print_r($colors->getColoredString("   [N] => New director  \n", "cyan"));

        print_r("\n");
        $act = readline("==>");
        if ((string)$act === "n" || (string)$act === "N") {
            print_r($colors->getColoredString(" Name of the new directory \n", "cyan"));
            $newDir = readline("==>");
            $dir .= "/" . $newDir;
            if (!mkdir($dir, 0777) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
            self::getDir($dir.'/', $newDir);

        }
        elseif ((int)$act === 0) {
            return $dir;
        }
        elseif ((int)$act > 0) {
            $act = (int)$act-1 ;
            $dir .= "/" . $directory[$act];
            self::getDir($dir, $directory[$act]);
        }
        return $dir;
    }


}