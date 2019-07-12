<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 10/05/19
 * Time: 17:05
 */

namespace App\Controllers;

use App\Validators\ProductValidator;

class ProductsController extends AppController
{
    public function __construct(){
        $this->loadModel('Product');
        $this->loadModel('Order');
        $this->loadModel('ProductOrder');
    }

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
            $result = $this->Product->all();
            if ($result) {
                $data['Product'] = $result;
                $msg = "Affichage de tous les produits";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        }
    }

    public function donate()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);

        if (!empty($json) ) {
            if (\AppFactory::getInstance()->getSession()->verifySessionC($json["id"], $json["jwt"])) {
                if (array_key_exists("products", $json) && !empty($json["products"])) {
                    foreach ($json["products"] as $key => $product) {
                        $validator = new ProductValidator();
                        $errors = $validator->validProduct($product);
                        if(count($errors) === 1 && array_key_exists(5,$errors)){
                            $product["size"] = $product["quantity"] . " unit";
                            $errors = null;
                        }
                        if(!array_key_exists("category",$product)) $product["category"] = $product["name"];
                        if(!array_key_exists("ingredients",$product)) $product["ingredients"] = $product["name"];

                        if (!empty($errors)) {
                            $data['errors'] = $errors;
                            $data['produit'] = $product;
                            $msg = "Produit incorrecte";
                            $this->response_json(false, $data, $msg);
                            exit(0);
                        }
                        $json["products"][$key] = $this->updateSize($product);
                    }

                    foreach ($json["products"] as $key => $product) {
                        if (!$this->Product->isExist($product)) $json["products"][$key]["id"] = $this->Product->add($product);
                        else $json["products"][$key]["id"] = $this->Product->getID($product)->ID;
                    }
                    $order = new OrdersController();
                    $idOrder = $order->add([
                        "type"=>"collect",
                        "status"=>"to_validate",
                        "creator"=>$json["id"]
                    ]);

                    $po = new ProductOrdersController();
                    $po->add($idOrder,$json["products"]);
                    $msg = "Donation validée";
                    $this->response_json(true, $json, $msg);
                }
            }
        }
    }

    private function updateSize($product){
        $size = $product["size"];
        $sizeExplode = explode(" ",$size);

        switch($sizeExplode[1]){
            case "cl":
                $product["volume"] = $sizeExplode[0]*10;
                $product["unit"] = "cm3";
                break;

            case "g":
                $product["volume"] = $sizeExplode[0];
                $product["unit"] = "cm3";
                break;

            case "l":
            case "kg":
                $product["volume"] = $sizeExplode[0];
                $product["unit"] = "dm3";
                break;

            default:
                $product["volume"] = $sizeExplode[0];
                $product["unit"] = $sizeExplode[1];
                break;
        }
        return $product;
    }
}