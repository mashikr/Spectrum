<?php

namespace Core;

class view {

    public static function render ($view, $args = []) {

        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view";

        if (is_readable($file)) {
            require $file;
        } else {
            // echo "$file not found";
            throw new \Exception("$file not found");
        }
    }

    public static function renderTemplate($template, $args = []) {
        echo static::getTemplate($template, $args );
    }

    public static function getTemplate($template, $args = []) {
        static $twig = null;
        
        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('flash_msg', \App\Flash::getMessage());
            $twig->addGlobal('folder', \App\Config::FOLDER);
            $twig->addGlobal('url_root', \App\Config::URL_ROOT);
            
            
            
            if (isset($_SESSION['user'])) {
                $twig->addGlobal('user', $_SESSION['user']);
            }
        }

        return $twig->render($template, $args);
    }
}

?>