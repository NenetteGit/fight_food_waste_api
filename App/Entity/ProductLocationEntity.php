<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-28
 * Time: 22:44
 */

namespace App\Entity;


use Core\Entity\Entity;

class ProductLocationEntity extends Entity
{
    public function getID()
    {
        return $this->id;
    }
}
