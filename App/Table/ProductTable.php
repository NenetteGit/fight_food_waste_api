<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 26/03/2019
 * Time: 20:28
 */

namespace App\Table;

use AppFactory;
use Core\Table\Table;

class ProductTable extends Table
{
    public function isExist($product){
        return $this->query("SELECT 1 FROM product WHERE reference=? AND expiration_date=?",[$product["reference"],$product["expiration_date"]],true);
    }

    public function getID($product){
        return $this->query("SELECT ID FROM product where reference=? AND expiration_date=?",[$product["reference"],$product["expiration_date"]],true);
    }

    public function add($product){
        if($this->create([
            "reference" => $product["reference"],
            "expiration_date" => $product["expiration_date"],
            "name" => $product["name"],
            "ingredients" => $product["ingredients"],
            "category" => $product["category"],
            "volume" => $product["volume"],
            "unit" => $product["unit"]
        ])) return AppFactory::getInstance()->getDb()->lastInsertId();
        else return false;
    }

    public function find($id){
        return $this->query("SELECT * FROM product where product.id=?",[$id],true);
    }

    public function getProductByExpirationDate(){
        return $this->query('SELECT * FROM ' . $this->table . ' ORDER BY expiration_date', [],true);
    }



}
