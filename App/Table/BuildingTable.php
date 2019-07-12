<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 10/05/2019
 * Time: 00:15
 */

namespace App\Table;

use Core\Table\Table;

class BuildingTable extends Table
{

    public function isExist($label, $city, $zipcode, $country)
    {
        return $this->query('SELECT 1 FROM ' . $this->table . ' WHERE address = (SELECT id FROM address WHERE label = ? AND city = ? AND zipcode = ? AND country = (SELECT id FROM country WHERE label = ?));', [$label, $city, $zipcode, $country], true);
    }

    public function getBuildingTypeById($id) {
        return $this->query('SELECT `type` FROM ' . $this->table . ' WHERE `id` = ?;', [$id], true);
    }

    public function getHouseIdByAddress($addressId) {
        return $this->query('SELECT `id` FROM ' . $this->table . ' WHERE `type` = \'house\' AND `address` = ?;', [$addressId], true);
    }

    public function getBuildingsByAddress($address) {
        return $this->query('SELECT * FROM ' . $this->table . ' WHERE `address` = ?;', [$address]);
    }

    public function getBuildingById($id) {
        return $this->query('SELECT * FROM ' . $this->table . ' WHERE `id` = ?;', [$id], true);
    }

    public function getBuildingByMemberId($race) {
        return $this->query('SELECT building.id FROM ' . $this->table . ', member, `order` WHERE building.id = member.building AND member.id = order.creator AND order.race = ?;', [$race], true);
    }

    public function getBuildingIdByAddress($warehouse, $city, $country) {
        return $this->query('SELECT building.id FROM building, address, country WHERE `building.type` = `Entrepôt` AND `building.label` = ? AND `address.city` = ? AND `address.country` = ?);', [$warehouse, $city, $country]);
    }

}
