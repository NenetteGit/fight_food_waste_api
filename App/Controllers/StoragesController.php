<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 10/05/19
 * Time: 16:02
 */

namespace App\Controllers;

class StoragesController extends AppController
{
    public function __construct(){ $this->loadModel('Storage'); }

    public function show(){
        header('Content-Type: application/json');
        if (isset($_GET['location']) && !empty($_GET['location'])) {
            $result = $this->Storage->findByLocation($_GET['location']);
            if ($result) {
                $data['storage'] = $result;
                $msg = "Storage place: {$_GET['location']}";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Aucun Storage correspondant";
                $this->response_json(true, null, $msg);
            }
        } else {
            $result = $this->Storage->all();
            if ($result) {
                $data['Storage'] = $result;
                $msg = "Affichage de tous les storages";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        }
    }
}