<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 11/05/19
 * Time: 20:51
 */

namespace App\Table;


use Core\Table\Table;

class OrderTable extends Table
{
    public function delete($id){
        $this->query("DELETE FROM `product_order` where product_order.order = ?", [$id],true);
        $this->query("DELETE FROM `order` where id=?",[$id],true);
    }

    public function accept($id){
        $this->query("UPDATE `order` SET status = ?, validated_at = ?, validator = ? where id = ?", ["validated", $id],true);
    }

    public function status($id){
        return $this->query("SELECT status FROM `order` where order.id = ?", [$id],true);
    }

    public function getOrdersId($id){
        return $this->query("SELECT id FROM `order` where race = ?",[$id]);
    }

    public function getOrdersByRace($race) {
        return $this->query('SELECT * FROM `order` WHERE `race` IN (?)', [$race]);
    }
}
