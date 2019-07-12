<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 11/05/19
 * Time: 21:15
 */

namespace App\Table;


use Core\Database\MySqlDatabase;
use Core\Table\Table;

class ProductOrderTable extends Table
{
    protected $table;

    public function __construct(MySqlDatabase $db)
    {
        $this->table = "product_order";
        parent::__construct($db);
    }

    public function getOrderAndProducts($id){
        $response["order"] = $this->query("SELECT * FROM `order` WHERE order.id=?",[$id],true);
        $response["products"] = $this->query("SELECT * FROM product,product_order WHERE product.id IN (SELECT product_order.product FROM product_order WHERE product_order.order=?) GROUP BY product.id",[$id]);
        return $response;
    }

    public function getAllProductOrder(){
        return $this->query("SELECT * FROM product_order, `order`, product WHERE product_order.product = product.id AND product_order.order = order.id AND order.race IS NULL AND `order`.status = 'validated'", []);
    }

    public function getAllOrders(){
        return $this->query("SELECT DISTINCT `product_order`.`order` FROM product_order, `order`, product WHERE product_order.product = product.id AND product_order.order = order.id AND order.race IS NULL AND `order`.status = 'validated'", []);
    }

    public function getProductsId($id){
        return $this->query('SELECT product FROM product_order WHERE product_order.order = ?', [$id], true);
    }

}
