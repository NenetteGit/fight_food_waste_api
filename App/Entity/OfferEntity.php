<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 28/03/2019
 * Time: 01:04
 */

namespace App\Entity;


use Core\Entity\Entity;

class OfferEntity extends Entity
{
    public function getUrl()
    {
        return 'index.php?p=offers.show&id=' . $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getPeriod()
    {
        return $this->period;
    }

}
