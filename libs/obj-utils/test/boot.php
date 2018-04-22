<?php
/**
 * phpunit
 */

error_reporting(E_ALL);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Shanghai');

spl_autoload_register(function ($class) {
    $file = null;

    if (0 === strpos($class,'MyLib\ObjUtil\Example\\')) {
        $path = str_replace('\\', '/', substr($class, strlen('MyLib\ObjUtil\Example\\')));
        $file = dirname(__DIR__) . "/example/{$path}.php";
    } elseif (0 === strpos($class,'MyLib\ObjUtil\Test\\')) {
        $path = str_replace('\\', '/', substr($class, strlen('MyLib\ObjUtil\Test\\')));
        $file = __DIR__ . "/{$path}.php";
    } elseif (0 === strpos($class,'MyLib\ObjUtil\\')) {
        $path = str_replace('\\', '/', substr($class, strlen('MyLib\ObjUtil\\')));
        $file = dirname(__DIR__) . "/src/{$path}.php";
    }

    if ($file && is_file($file)) {
        include $file;
    }
});
