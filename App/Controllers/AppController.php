<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 02/04/2019
 * Time: 18:51
 */

namespace App\Controllers;

use Core\Controllers\Controller;
use AppFactory;

class AppController extends Controller
{
    protected function loadModel($model)
    {
        $this->$model = AppFactory::getInstance()->getTable($model);
    }
}
