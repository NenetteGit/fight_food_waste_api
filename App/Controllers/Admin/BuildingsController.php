<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-16
 * Time: 15:32
 */

namespace App\Controllers\Admin;


use AppFactory;

class BuildingsController extends AppController
{
    public function __construct()
    {
        if(AppFactory::getInstance()->getSession()->verifySession("admin")) {
            $this->loadModel('Country');
            $this->loadModel('Address');
            $this->loadModel('Building');
        }
        else AppFactory::forbidden();

    }

    public function show()
    {
        header('Content-Type: application/json');
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $result = $this->Building->find($_GET['id']);
            if ($result) {
                $data['building'] = $result;
                $msg = "Affichage du bâtiment: {$_GET['id']}";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        } else {
            $result = $this->Building->all();
            if ($result) {
                $data['building'] = $result;
                $msg = "Affichage de tous les bâtiments";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        }
    }

    public function add()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);

        if (!empty($json)) {
            $address = $this->Address->getAddressIdByCoordinates($json['post']['latitude'], $json['post']['longitude']);
            if (!$address) {
                $result = $this->Address->create([
                    'label' => trim($json['post']['street']),
                    'city' => trim($json['post']['city']),
                    'zipcode' => trim($json['post']['zipcode']),
                    'latitude' => trim($json['post']['latitude']),
                    'longitude' => trim($json['post']['longitude']),
                    'country' => trim($json['post']['country']),
                ]);
                if ($result) $address = $this->Address->getAddressIdByCoordinates($json['post']['latitude'], $json['post']['longitude']);
            }
            if (!$this->Building->isExist($json['post']['label'], $json['post']['city'], $json['post']['zipcode'], $json['post']['country']))
            {
                $result = $this->Building->create([
                    'label' => trim($json['post']['label']),
                    'capacity' => trim($json['post']['capacity']),
                    'unit' => 'm3',
                    'type' => trim($json['post']['type']),
                    'address' => $address->id
                ]);
                if ($result) {
                    $msg = "Le bâtiment a bien été ajouté";
                    $this->response_json(true, null, $msg);
                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
            } else {
                $msg = "Le bâtiment exist déjà";
                $this->response_json(false, null, $msg);
            }
        } else {
            $msg = "Le bâtiment exist déjà";
            $this->response_json(false, null, $msg);
        }
    }

    public function delete()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);

        if (!empty($json)) {
            if ($this->Building->find($_GET['id'])) {
                $result = $this->Building->delete($_GET['id']);
                if ($result) {
                    $msg = "Le bâtiment a bien été supprimé";
                    $this->response_json(true, null, $msg);
                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
            }
        }
    }
}
