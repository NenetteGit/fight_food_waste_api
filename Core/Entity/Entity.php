<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 27/03/2019
 * Time: 19:16
 */

namespace Core\Entity;

class Entity
{

    public function __get($key)
    {
        $method = 'get' . ucfirst($key);
        $this->$key = $this->$method();
        return $this->$key;
    }
}