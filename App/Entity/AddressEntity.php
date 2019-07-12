<?php
namespace App\Entity;

use Core\Entity\Entity;

class AddressEntity extends Entity
{
    public function getUrl()
    {
        return 'index.php?p=members.show&id=' . $this->id;
    }
}