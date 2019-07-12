<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-17
 * Time: 11:54
 */

namespace App\Controllers;


class AddressesController extends AppController
{
    public function __construct()
    {
        $this->loadModel('Country');
        $this->loadModel('Address');
    }

    public function show()
    {
        header('Content-Type: application/json');
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $result = $this->Address->find($_GET['id']);
            if ($result) {
                $data['address'] = $result;
                $msg = "Affichage du bâtiment: {$_GET['id']}";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        } else {
            $result = $this->Address->all();
            if ($result) {
                $data['address'] = $result;
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
            $result = $this->Address->create([
                'label' => $json['street'],
                'city' => $json['city'],
                'zipcode' => $json['zipcode'],
                'latitude' => $json['latitude'],
                'longitude' => $json['longitude'],
                'country' => $json['country'],
            ]);

            if ($result) {
                $msg = "L'adresse a bien été ajouté";
                $this->response_json(true, null, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        }
    }
}
