<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 20/05/19
 * Time: 15:50
 */

namespace App\Controllers\Admin;

use AppFactory;
use Core\Controllers\Controller;

class MembersController extends AppController
{
    public function __construct()
    {
        if(AppFactory::getInstance()->getSession()->verifySession("employee")) $this->loadModel('Member');
        else AppFactory::forbidden();
    }

    public function show(){
        if(isset($_GET["id"]) && !empty($_GET["id"])){
            $data = $this->Member->find($_GET["id"]);
            Controller::response_json(true,$data);
        }
        else {
            $data['members'] = $this->Member->all();
            Controller::response_json(true, $data);
        }
    }
}