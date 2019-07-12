<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 27/03/2019
 * Time: 01:06
 */

namespace Core;


class Config
{
    private $settings = [];
    private static $_instance;

    public function __construct($file)
    {
        $this->settings = require($file);
    }

    public static function getInstance($file)
    {
        if(self::$_instance === null) {
            self::$_instance = new Config($file);
        }
        return self::$_instance;
    }

    public function get($key)
    {
        if (!isset($this->settings[$key])) {
            return null;
        }
        return $this->settings[$key];
    }
}