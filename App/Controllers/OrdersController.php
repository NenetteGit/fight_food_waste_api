<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 11/05/19
 * Time: 20:50
 */

namespace App\Controllers;

use App\Validators\OrderValidator;
use AppFactory;

class OrdersController extends AppController
{
    public function __construct()
    {
        $this->loadModel('Order');
    }

    public function add($fields)
    {
        $validator = new OrderValidator();
        if ($validator->verifyFields($fields)) {
            if ($this->Order->create([
                "type" => $fields["type"],
                "status" => $fields["status"],
                "creator" => $fields["creator"],
            ])) return AppFactory::getInstance()->getDb()->lastInsertId();
            else return false;
        }
        else return false;
    }
}