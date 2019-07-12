<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 04/06/19
 * Time: 13:55
 */

namespace App\Controllers\Admin;


use AppFactory;

class VolunteersController extends AppController
{
    public function __construct()
    {
        if(AppFactory::getInstance()->getSession()->verifySession("volunteer")) $this->loadModel("Member");
        else AppFactory::forbidden();
    }

    public function acceptCandidate(){

    }

    public function refuseCandidate(){

    }

}