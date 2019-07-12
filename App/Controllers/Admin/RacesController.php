<?php
namespace App\Controllers\Admin;

use AppFactory;
use DateTime;
use DateTimeZone;
use Exception;

/**
 * Created by PhpStorm.
 * User: alex_rdgu
 * Date: 2019-05-12
 * Time: 01:01
 */

class RacesController extends AppController
{
    public function __construct()
    {
        if(AppFactory::getInstance()->getSession()->verifySession("admin")) {
            $this->loadModel('Country');
            $this->loadModel('Address');
            $this->loadModel('Building');
            $this->loadModel('Vehicle');
            $this->loadModel('Member');
            $this->loadModel('Race');
            $this->loadModel('Product');
            $this->loadModel('ProductOrder');
            $this->loadModel('Order');
            $this->loadModel('Storage');
        }
        else AppFactory::forbidden();
    }

    public function show()
    {
        header('Content-Type: application/json');
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $result = $this->Race->find($_GET['id']);
            if ($result) {
                $data['race'] = $result;
                $msg = "Affichage de l'itinéraire: {$_GET['id']}";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        } else {
            $result = $this->Race->all();
            if ($result) {
                $data['race'] = $result;
                $msg = "Affichage de tous les itinéraires";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Aucun race en BDD";
                $this->response_json(false, null, $msg);
            }
        }
    }

    public function consultDetails()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);

        $infos = $this->Address->getInfosForRace($json['id']);
        echo(json_encode($infos));


    }

    public function add()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);
        if (!empty($json)) {
            //Get all the products order not already in a race
            $product_orders = $this->ProductOrder->getAllProductOrder();
            $trucks = $this->Vehicle->getVehiclesAvailable($json['post']['vehicle_taken_at'], $json['post']['building']);

            //Total of the volume of each order based on the unit volume * quantity
            $totalVolume = 0;
            for ($i=0; $i<count($product_orders);$i++){
                $totalVolume += $product_orders[$i]->volume * $product_orders[$i]->quantity;
            }
            //Get the best truck for the $totalVolume just got before
            foreach ($trucks as $truck){
                if($truck->capacity > $totalVolume && $totalVolume >= 0.65 * $truck->capacity){
                    $minValue = $truck->capacity - $totalVolume;
                    $bestTruck = $truck;
                }

            }
            //If we have a truck adapted to the race, make the race and update all the orders with the good conductor and the good race
            if (isset($minValue) && isset($bestTruck)){
                if ($json['post']['type'] = "1") $type = "harvest";
                if ($json['post']['type'] = "0") $type = "delivery";
                $result = $this->Race->createRace($type, $json['post']['vehicle_taken_at'], $bestTruck->id);
                if ($result) {
                    $orders = [];
                    foreach ($product_orders as $key => $product_order) {
                        if (!in_array($product_order->order, $orders)) {
                            $orders[$key] = $product_order->order;
                        }
                    }
                    foreach ($orders as $order) {
                        $this->Order->update($order, [
                            'race' => $result,
                            'driver' => $json['post']['conductor'],
                            'status' => 'active'
                        ]);
                    }
                    $msg = "Le véhicule immatriculé : " . $bestTruck->license_plate . " d'une capacité de : " . $bestTruck->capacity . " " . $bestTruck->unit . " est disponible et adapté à la récolte ! L'itinéraire a bien été créé";
                    $this->response_json(true, null, $msg);
                }else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }

            }else {
                $msg = "Les véhicules disponibles ne sont pas adaptés à la récolte";
                $this->response_json(false, null, $msg);
            }
        }
    }

    public function terminated()
    {
        try {
            $orders = $this->Order->getOrdersByRace($_GET['id']);
            $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
            foreach ($orders as $order) {
                $this->Order->update($order->id, [
                    'status' => 'done',
                    'received_at' => $now->format('Y-m-d H:i:s')
                ]);
            }
            $result = $this->Race->update($_GET['id'], [
                'vehicle_returned_at' => $now->format('Y-m-d H:i:s'),
                'status' => 'terminated'
            ]);
            if ($result) {
                $msg = "La course est terminée";
                $this->response_json(true, null, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        } catch (Exception $e) {
            $this->response_json(false, null, $e->getMessage());
        }
    }

}
