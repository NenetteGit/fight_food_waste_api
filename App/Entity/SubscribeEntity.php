<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-06-15
 * Time: 17:01
 */

namespace App\Entity;


use Core\Entity\Entity;

class SubscribeEntity extends Entity
{
    public function getUrl()
    {
        return 'index.php?p=subscribes.show&id=' . $this->id;
    }
}