<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-12
 * Time: 01:00
 */

namespace App\Entity;

use Core\Entity\Entity;

class RaceEntity extends Entity
{
    public function getID()
    {
        return $this->id;
    }

    public $id;


}
