<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-10
 * Time: 15:00
 */

namespace App\Table;

use Core\Table\Table;

class VehicleTable extends Table
{

    public function isExist($license_plate)
    {
        return $this->query('SELECT 1 FROM vehicle WHERE license_plate = ?;', [$license_plate], true);
    }

    public function getVehicleTypeById($id) {
        return $this->query('SELECT `type` FROM ' . $this->table . ' WHERE `id` = ?;', [$id], true);
    }

    public function getVehiclesAvailable($vehicle_taken_at, $id)
    {
        return $this->query('SELECT * FROM ' . $this->table . ' WHERE id IN (SELECT truck FROM race WHERE vehicle_returned_at < ?) 
        UNION SELECT * FROM ' . $this->table . ' WHERE NOT id IN (SELECT truck FROM race) AND warehouse = ?', [$vehicle_taken_at, $id]);
    }

    public function getVehiclesByType($type) {
        return $this->query('SELECT * FROM ' . $this->table . ' WHERE `type` = ?;', [$type]);
    }

    public function getVehicleWarehouseByLicensePlate($license_plate) {
        return $this->query('SELECT `warehouse` FROM ' . $this->table . ' WHERE `license_plate` = ?;', [$license_plate], true);
    }

    public function getVehiclesByWarehouse($city, $country) {
        return $this->query('SELECT * FROM ' . $this->table . ' WHERE `warehouse` = (SELECT * FROM building, address, country WHERE `building.type` = `Entrepôt` AND `address.city` = ? AND `address.country` = ?);', [$city, $country]);
    }

    public function getVehicleIdByBuilding($label, $capacity) {
        return $this->query('SELECT vehicle.id FROM ' . $this->table . ', building WHERE building.label = ? AND vehicle.capacity = ?;', [$label, $capacity]);
    }


}