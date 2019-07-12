<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 11/05/2019
 * Time: 20:02
 */

namespace App\Controllers;


use Core\Controllers\Controller;

class CountriesController extends AppController
{
    public function __construct()
    {
        $this->loadModel('Country');
    }

    public function show()
    {
        header('Content-Type: application/json');
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $result = $this->Country->find($_GET['id']);
            if ($result) {
                $data['countries'] = $result;
                $msg = "";
                Controller::response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                Controller::response_json(false, null, $msg);
            }
        } else {
            $result = $this->Country->all();
            if ($result) {
                $data['countries'] = $result;
                $msg = "";
                Controller::response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                Controller::response_json(false, null, $msg);
            }
        }
    }
}