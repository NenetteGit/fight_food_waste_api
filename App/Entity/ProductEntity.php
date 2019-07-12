<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 27/03/2019
 * Time: 02:13
 */

namespace App\Entity;

use Core\Entity\Entity;

class ProductEntity extends Entity
{
    public $ID;

    public function getId(){
        return $this->ID;
    }
}