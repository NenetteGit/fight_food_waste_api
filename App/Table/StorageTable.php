<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 10/05/19
 * Time: 16:23
 */

namespace App\Table;

use Core\Table\Table;

class StorageTable extends Table
{
    public function all()
    {
        return $this->query('SELECT * FROM ' . $this->table.';');
    }
    public function findByLocation($location)
    {
        return $this->query('SELECT * FROM ' . $this->table.' WHERE location = ?;',[$location]);
    }
}