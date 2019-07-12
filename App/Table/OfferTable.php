<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 28/03/2019
 * Time: 01:00
 */

namespace App\Table;

use Core\Table\Table;

class OfferTable extends Table
{
    /**
     * Retrieve the last products
     * @return array
     */
    public function last()
    {
        return $this->query('SELECT * FROM offer ORDER BY id DESC;');
    }
}
