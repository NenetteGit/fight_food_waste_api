<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 14/06/19
 * Time: 18:30
 */

namespace App\Controllers\Admin;


use AppFactory;

class AppliantsController extends AppController
{
    public function __construct()
    {
        if (AppFactory::getInstance()->getSession()->verifySession("admin")){
            $this->loadModel('Member');
            $this->loadModel('Appliant');
            $this->loadModel('Address');
            $this->loadModel('Job');
        }
        else AppFactory::forbidden();
    }

    public function all(){
        $appliants = $this->Appliant->all();
        foreach ($appliants as $key => $appliant){
            $appliants[$key]->member = $this->Member->find($appliant->member);
            $appliants[$key]->address = $this->Address->getAddressByBuilding($appliant->member->building);
            $appliants[$key]->job = $this->Job->jobById($appliant->job);
        }
        $this->response_json(true,$appliants);
    }

    public function accept(){
        if(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
            if($this->Appliant->accept($_GET["id"])){
                $this->response_json(true,null,"appliance accepted");
                return;
            }
        }
        $this->response_json(false,null,"incorrect request");
    }

    public function refuse(){
        if(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
            if($this->Appliant->refuse($_GET["id"])){
                $this->response_json(true,null,"appliance refused");
                return;
            }
        }
        $this->response_json(false,null,"incorrect request");
    }

    public function retreat(){
        if(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
            if($this->Appliant->retreat($_GET["id"])){
                $this->response_json(true,null,"appliant retired");
                return;
            }
        }
        $this->response_json(false,null,"incorrect request");
    }
}