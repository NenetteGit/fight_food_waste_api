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

class OrdersController extends AppController
{
    public function __construct()
    {
        if(AppFactory::getInstance()->getSession()->verifySession("employee")) $this->loadModel('Order');
        else AppFactory::forbidden();
    }

    public function show(){
            $orders = $this->Order->query("SELECT * FROM `order`");
            Controller::response_json(true, $orders, "All orders returned");
    }

    public function refuse(){
        if(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
            $result = $this->Order->delete($_GET["id"]);
            Controller::response_json(true,$result);
        }
    }

    public function accept(){
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);

        if(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
            $result = $this->Order->update($_GET['id'], [
                'validator' => $json['post']['validator'],
                'validated_at' => $json['post']['validated_at'],
                'status' => 'validated'
            ]);
            if ($result) {
                $this->response_json(true, null, 'Commande validée');
            } else {
                $this->response_json(false, null, 'Une erreur s\'est produite');
            }
        }
    }

    public function status(){
        if(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric(($_GET["id"]))){
            $result = $this->Order->status($_GET["id"]);
            Controller::response_json(true,$result);
        }
    }
}