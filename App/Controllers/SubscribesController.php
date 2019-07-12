<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-06-15
 * Time: 16:56
 */

namespace App\Controllers;


class SubscribesController extends AppController
{
    public function __construct()
    {
        $this->loadModel('Offer');
        $this->loadModel('Subscribe');
    }

}