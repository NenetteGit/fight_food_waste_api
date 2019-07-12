<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 11/05/19
 * Time: 21:19
 */

namespace App\Controllers;

class ProductOrdersController extends AppController
{
    public function __construct()
    {
        $this->loadModel('ProductOrder');
    }

    public function add($orderID,$products){
        if(isset($orderID) && !empty($orderID) && isset($products) && !empty($products)){
            foreach ($products as $product){
                $this->ProductOrder->create([
                    "`order`"=>$orderID,
                    "product"=>$product["id"],
                    "quantity"=>$product["quantity"]
                ]);
            }
        }
    }
}