<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 20/05/19
 * Time: 16:53
 */

namespace App\Controllers\Admin;

use AppFactory;
use Core\Controllers\Controller;

class AddressesController extends AppController
{
    public function __construct()
    {
        if(AppFactory::getInstance()->getSession()->verifySession("employee")) $this->loadModel('Address');
        else AppFactory::forbidden();
    }

    public function showByBuilding(){
        if(isset($_GET["buildingID"]) && !empty($_GET["buildingID"])){
            $data = $this->Address->getAddressByBuilding($_GET["buildingID"]);
            Controller::response_json(true,$data);
        }
        else Controller::response_json(false,null);
    }
}