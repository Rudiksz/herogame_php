<?php

spl_autoload_register(function ($class) {
    if (strpos($class, 'Html') !== false)
        $file = 'templates/' . strtolower(str_replace('Html', '', $class)) . '.html.php';
    else if (strpos($class, 'Controller') !== false)
        $file = 'controllers/' . strtolower(str_replace('Controller', '', $class)) . '.class.php';
    else
        $file = 'engine/' . strtolower($class) . '.php';


    include $file;
});