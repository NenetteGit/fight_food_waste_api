<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 26/03/2019
 * Time: 17:37
 */

namespace Core;

class Autoloader
{
    /**
     * Save our autoloader
    */
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Include the matching file with the class called in parameter
     * @param $class: name of the class to load
     */
    static function autoload($class)
    {
        if(strpos($class, __NAMESPACE__ . '\\') === 0) {
           $class = str_replace(__NAMESPACE__ . '\\' , '', $class);
           $class = str_replace('\\', '/', $class);
            if(file_exists(__DIR__ . '/' . $class . '.php')) require_once __DIR__ . '/' . $class . '.php';
            else die(__DIR__ . '/' . $class . '.php');
        }
    }

}