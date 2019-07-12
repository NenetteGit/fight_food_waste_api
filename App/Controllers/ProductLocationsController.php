<?php
/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-28
 * Time: 20:47
 */

namespace App\Controllers;


class ProductLocationsController extends AppController
{
    public function __construct()
    {
        $this->loadModel('Building');
        $this->loadModel('ProductLocation');
    }

    public function show()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $result = $this->ProductLocation->getProductLocationById($_GET['id']);
            if ($result) {
                $data['vehicle'] = $result;
                $msg = "Affichage de l'emplacement de produit d'id: {$_GET['id']}";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        } else {
            $result = $this->ProductLocation->getAllProductLocation();
            if ($result) {
                $data['vehicles'] = $result;
                $msg = "Affichage de tous les emplacements de produit";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Aucun emplacement de produit en BDD";
                $this->response_json(false, null, $msg);
            }
        }
    }

    public function addLocation($store)
    {
        header('Content-Type: application/json');
        //$content = file_get_contents('php://input');
        //$json = json_decode($content, true);

            //var_dump($json);

                //$storeFinal = $this->Building->getBuildingByMemberId($store);


                //var_dump($warehouse);
                //die("toto");
                //if ($this->Vehicle->validUnit($json['unit'])){
                $result = $this->ProductLocation->createProductLocation($store);

                if ($result) {
                    $msg = "L'emplacement a bien été ajouté";
                    $this->response_json(true, null, $msg);
                    //$address = $this->Address->getAddressId($json['address'], $country->id);


                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
                /*}else{
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }*/
    }
}