<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-28
 * Time: 20:46
 */

namespace App\Table;


use Core\Table\Table;

class ProductLocationTable extends Table
{
    public function getAllProductLocation()
    {
        return $this->query('SELECT * FROM product_location;');
    }

    public function getProductLocationById($id)
    {
        return $this->query('SELECT * FROM product_location WHERE id = ?;', [$id], true);
    }

    public function createProductLocation($line, $place, $id)
    {
        return $this->query('INSERT INTO product_location (line, place, store) VALUES (?, ?, ?);', [$line, $place, $id]);
    }
}