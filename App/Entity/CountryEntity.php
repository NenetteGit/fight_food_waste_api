<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 28/03/2019
 * Time: 01:04
 */

namespace App\Entity;


use Core\Entity\Entity;

class CountryEntity extends Entity
{
    public function getUrl()
    {
        return 'index.php?p=members.show&id=' . $this->id;
    }
}