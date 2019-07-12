<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-10
 * Time: 16:07
 */

namespace App\Controllers\Admin;

use AppFactory;

class VehiclesController extends AppController
{
    public function __construct()
    {
        if(AppFactory::getInstance()->getSession()->verifySession("admin")) {
            $this->loadModel('Building');
            $this->loadModel('Vehicle');
        }
        else AppFactory::forbidden();
    }

    public function show()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $result = $this->Vehicle->find($_GET['id']);
            if ($result) {
                $data['vehicle'] = $result;
                $msg = "Affichage du véhicule: {$_GET['id']}";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        } else {
            $result = $this->Vehicle->all();
            if ($result) {
                $data['vehicles'] = $result;
                $msg = "Affichage de tous les véhicules";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Aucun véhicule en BDD";
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
            if (!$this->Vehicle->isExist($json['post']['license_plate'])) {
                $warehouse = $this->Building->getBuildingById($json['post']['warehouse']);
                $result = $this->Vehicle->create([
                    'license_plate' => $json['post']['license_plate'],
                    'capacity' => $json['post']['capacity'],
                    'type' => $json['post']['type'],
                    'unit' => 'm3',
                    'warehouse' => $warehouse->id
                ]);

                if ($result) {
                    $msg = "Le véhicule a bien été ajouté";
                    $this->response_json(true, null, $msg);
                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
            }else {
                $msg = "Le véhicule existe déjà";
                $this->response_json(false, null, $msg);
            }
        }
    }

    public function edit()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);

        if (!empty($json)) {
            if ($this->Vehicle->find($json['post']['id']))
            {
                $result = $this->Vehicle->update($json['post']['id'], [
                    'license_plate' => $json['post']['license_plate'],
                    'capacity' => $json['post']['capacity'],
                    'type' => $json['post']['typeEdit'],
                    'unit' => 'm3',
                    'warehouse' => $json['post']['warehouseEdit']
                ]);
                if ($result) {
                    $msg = "Le véhicule a bien été modifié";
                    $this->response_json(true, null, $msg);
                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
            } else {
                $msg = "Le véhicule d'id: {$json['post']['id']} n'existe pas";
                $this->response_json(false, null, $msg);
            }
        }
    }

    public function delete()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);

        if (!empty($json)) {
            if ($this->Vehicle->find($_GET['id'])) {
                $result = $this->Vehicle->delete($_GET['id']);
                if ($result) {
                    $msg = "Le véhicule a bien été supprimé";
                    $this->response_json(true, null, $msg);
                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
            }
        }
    }
}
