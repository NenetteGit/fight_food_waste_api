<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 09/05/2019
 * Time: 23:11
 */

namespace App\Table;

use Core\Table\Table;

class AddressTable extends Table
{
    public function getAddressId($label, $city, $zipcode) {
        return $this->query('SELECT `id` FROM ' . $this->table . ' WHERE `label` = ? AND `city` = ? AND `zipcode` = ?;', [$label, $city, $zipcode], true);
    }

    public function addressExist($label, $city, $zipcode)
    {
        return $this->query('SELECT 1 FROM ' . $this->table . ' WHERE `label` = ? AND `city` = ? AND `zipcode` = ?;', [$label, $city, $zipcode], true);
    }

    public function getAddressIdByCoordinates($latitude, $longitude)
    {
        return $this->query('SELECT `id` FROM ' . $this->table . ' WHERE `latitude` = ? AND `longitude` = ?;', [$latitude, $longitude], true);
    }

    public function getAddressByBuilding($id){
        return $this->query("SELECT * FROM address WHERE address.id=(SELECT building.address FROM building where building.id=?)",[$id],true);
    }

    public function getInfosForRace($id){
        return $this->query("SELECT address.label, address.city, address.zipcode from address, building, member, `order`
          WHERE order.race = ? AND member.id = order.creator AND building.id = member.building AND address.id = building.id",[$id]);
    }
}
