<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 11/05/19
 * Time: 20:50
 */

namespace App\Controllers\Admin;

use AppFactory;
use Core\Controllers\Controller;

class ProductOrdersController extends AppController
{
    public function __construct()
    {
        if(AppFactory::getInstance()->getSession()->verifySession("employee")) $this->loadModel('ProductOrder');
        else AppFactory::forbidden();
    }

    public function full(){
        if(isset($_GET["orderID"]) && !empty($_GET["orderID"])) {
            $orders = $this->ProductOrder->getOrderAndProducts($_GET["orderID"]);
            Controller::response_json(true, $orders, "All product orders returned");
        }
        else{
            Controller::response_json(false,null,"Missing orderID parameter");
        }
    }
}