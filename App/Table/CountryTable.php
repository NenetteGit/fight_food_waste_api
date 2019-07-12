<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 09/05/2019
 * Time: 21:52
 */

namespace App\Table;

use Core\Table\Table;

class CountryTable extends Table
{
    public function getCountryIdByLabel($label) {
        return $this->query('SELECT `id` FROM ' . $this->table . ' WHERE `label` = ?;', [$label], true);
    }

    public function getAllCountryNames()
    {
        return $this->query('SELECT DISTINCT `label` FROM ' . $this->table . ' ORDER BY `label` ASC;');
    }
}