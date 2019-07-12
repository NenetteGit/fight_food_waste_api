<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 02/04/2019
 * Time: 18:18
 */

namespace App\Controllers;

use AppFactory;
use Core\Validator\Validator;
use DateTime;
use DateTimeZone;

class OffersController extends AppController
{
    public function __construct()
    {
        $this->loadModel('Offer');
        $this->loadModel('Subscribe');
    }

    public function show()
    {
        header('Content-Type: application/json');
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $result = $this->Offer->find($_GET['id']);
            if ($result) {
                $data['offers'] = $result;
                $msg = "Affichage de l'offre: {$_GET['id']}";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        } else {
            $result = $this->Offer->all();
            if ($result) {
                $data['offers'] = $result;
                $msg = "Affichage de toutes les offres";
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
        if (!empty($_POST)) {
            $validator = new Validator();
            if ($validator->validForm($_POST)) {
                $result = $this->Offer->create([
                    'title' => $_POST['title'],
                    'price' => $_POST['price'],
                    'period' => $_POST['period'],
                ]);
                if ($result) {
                    $msg = "L'offre a bien été ajoutée";
                    $this->response_json(true, null, $msg);
                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
            } else {
                $msg = "Les saisies sont incorrectes";
                $this->response_json(false, null, $msg);
            }
        } else {
            $msg = "Les champs sont vides";
            $this->response_json(false, null, $msg);
        }
    }

    public function edit()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);
        if (!empty($json)) {
            if ($this->Offer->find($_GET['id'])) {
                $result = $this->Offer->update($_GET['id'], [
                    'title' => $json['title'],
                    'price' => $json['price'],
                    'period' => $json['period'],
                ]);
                if ($result) {
                    $msg = "L'offre a bien été modifié";
                    $this->response_json(true, null, $msg);
                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
            } else {
                $msg = "L'offre d'id: {$_GET['id']} n'existe pas";
                $this->response_json(false, null, $msg);
            }
        } else {
            $msg = "Les champs sont vides";
            $this->response_json(false, null, $msg);
        }
    }

    public function delete()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);

        if (!empty($json)) {
            if ($this->Offer->find($json['id'])) {
                $result = $this->Offer->delete($json['id']);
                if ($result) {
                    $msg = "L'offre a bien été supprimée";
                    $this->response_json(true, null, $msg);
                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
            } else {
                $msg = "L'offre d'id: {$_POST['id']} n'existe pas";
                $this->response_json(false, null, $msg);
            }
        } else {
            $msg = "Les champs sont vides";
            $this->response_json(false, null, $msg);
        }
    }

    public function subscribe(){
        if(AppFactory::getInstance()->getSession()->verifySession() && isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
            $post['token'] = AppFactory::getInstance()->getSession()->getToken();
            $offer = $this->Offer->find($_GET['id']);
            $offerEnd = $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
            $post['offer_end'] = $offerEnd->modify('+' . $offer->period . ' day')->format('Y-m-d H:i:s');
            if($this->Subscribe->subscribe($_GET["id"], $post)){
                $this->response_json(true, null, 'Souscription réussi');
            }
            else $this->response_json(false, null);
        }
    }
}
