<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 06/06/19
 * Time: 15:14
 */

namespace App\Controllers;


use Core\Controllers\Controller;

class JobsController extends AppController
{
    public function __construct()
    {
        $this->loadModel('Job');
    }

    public function show(){
        $jobs = $this->Job->getAll();
        Controller::response_json(true,$jobs    );
    }
}