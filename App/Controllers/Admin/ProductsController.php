<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 20/05/19
 * Time: 16:40
 */

namespace App\Controllers\Admin;

use AppFactory;
use Core\Controllers\Controller;

class ProductsController extends AppController
{
    public function __construct()
    {
        if (AppFactory::getInstance()->getSession()->verifySession("employee")) $this->loadModel('Product');
        else AppFactory::forbidden();
    }

    public function show(){
        if(isset($_GET["id"]) && !empty($_GET["id"])){
            $result = $this->Product->find($_GET["id"]);
            Controller::response_json(true,$result);
        }
        else Controller::response_json(false,null);
    }
}