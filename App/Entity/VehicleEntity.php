<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-10
 * Time: 14:59
 */

namespace App\Entity;


use Core\Entity\Entity;

class VehicleEntity extends Entity
{

    public function getID()
    {
        return $this->id;
    }
}