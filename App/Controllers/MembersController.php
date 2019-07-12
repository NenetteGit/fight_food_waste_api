<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 08/04/2019
 * Time: 01:18
 */

namespace App\Controllers;

use App\Validators\RegisterValidator;
use Core\Auth\DBAuth;
use AppFactory;
use Core\Validator\Validator;

class MembersController extends AppController
{
    public function __construct()
    {
        $this->loadModel('Member');
        $this->loadModel('Address');
        $this->loadModel('Country');
        $this->loadModel('Building');
        $this->loadModel('Appliant');
    }

    public function show()
    {
        header('Content-Type: application/json');
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $result = $this->Member->find($_GET['id']);
            if ($result) {
                $data['members'] = $result;
                $msg = "Affichage de l'utilisateur: {$_GET['id']}";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        } else {
            $result = $this->Member->all();
            if ($result) {
                $data['members'] = $result;
                $msg = "Affichage de tous les utilisateurs";
                $this->response_json(true, $data, $msg);
            } else {
                $msg = "Une erreur s'est produite";
                $this->response_json(false, null, $msg);
            }
        }
    }

    public function login()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);
        if (!empty($json)) {
            $auth = new DBAuth(AppFactory::getInstance()->getDb());
            if ($id = $auth->login($json['emailLogin'], $json['pwdLogin'])){
                $jwt = AppFactory::getInstance()->getSession()->generateSession($id);
                $result["id"] = $id;
                $result["jwt"] = $jwt;
                $msg = "L'utilisateur a bien été authentifié";
                $this->response_json(true, $result, $msg);
            } else {
                $msg = "Identifiants incorrects";
                $this->response_json(false, null, $msg);
            }
        } else {
            $msg = "Aucune donnée";
            $this->response_json(false, null, $msg);
        }
    }

    public function signUp()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);

        if (!empty($json)) {
            $auth = new DBAuth(AppFactory::getInstance()->getDb());
            $validator = new RegisterValidator();
            if (empty($errors = $validator->validForm($json))) {
                if (!$auth->emailExist($json['emailSignUp'])) {
                    $address = $this->Address->getAddressIdByCoordinates($json['latitude'], $json['longitude']);
                    if (!$address) {
                        $result = $this->Address->create([
                            'label' => trim($json['street']),
                            'city' => trim($json['city']),
                            'zipcode' => trim($json['zipcode']),
                            'latitude' => $json['latitude'],
                            'longitude' => $json['longitude'],
                            'country' => $json['country'],
                        ]);
                        if (!$result) {
                            $msg = "Une erreur s'est produite lors de la création de l'adresse";
                            $this->response_json(false, null, $msg);
                            die();
                        }
                        $address = $this->Address->getAddressIdByCoordinates($json['latitude'], $json['longitude']);
                    }
                    $building = $this->Building->getHouseIdByAddress($address->id);
                    if (!$building) {
                        $json['status'] === 'user' ? $type = 'house' : $type = 'company';
                        $result = $this->Building->create([
                            'type' => $type,
                            'address' => $address->id
                        ]);
                        if (!$result) {
                            $msg = "Une erreur s'est produite lors de la création du Building";
                            $this->response_json(false, null, $msg);
                            die();
                        }
                        $building = $this->Building->getHouseIdByAddress($address->id);
                    }
                    $password = password_hash($json['pwdSignUp'], PASSWORD_BCRYPT);
                    $result = $this->Member->create([
                        'status' => $json['status'],
                        'firstname' => $json['firstname'],
                        'lastname' => $json['lastname'],
                        'gender' => $json['gender'],
                        'email' => $json['emailSignUp'],
                        'password' => $password,
                        'building' => $building->id,
                        'company_name' => $json['company'],
                        'phone' => $json['phone']
                    ]);
                    if ($result) {
                        $msg = "L'utilisateur a bien été ajouté";
                        $this->response_json(true, null, $msg);
                    } else {
                        $msg = "Une erreur s'est produite";
                        $this->response_json(false, null, $msg);
                    }
                } else {
                    $msg = "Email incorrect";
                    $this->response_json(false, null, $msg);
                }
            }
            else {
                $msg = "Données invalides";
                $this->response_json(false, $errors, $msg);
            }
        } else {
            $msg = "Aucune donnée";
            $this->response_json(false, null, $msg);
        }
    }

    public function edit()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);
        if (!empty($_POST)) {
            $validator = new Validator();
            if ($validator->validForm($_POST)) {
                if ($this->Member->find($_GET['id']))
                {
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $result = $this->Member->update($_GET['id'], [
                        'status' => $_POST['status'],
                        'firstname' => $_POST['firstname'],
                        'lastname' => $_POST['lastname'],
                        'gender' => $_POST['gender'],
                        'email' => $_POST['email'],
                        'password' => $password,
                        'address' => $_POST['address'],
                        'city' => $_POST['city'],
                        'zip_code' => $_POST['zip_code'],
                        'country' => $_POST['country'],
                        'phone' => $_POST['phone']
                    ]);
                    if ($result) {
                        $msg = "L'utilisateur a bien été modifié";
                        $this->response_json(true, null, $msg);
                    } else {
                        $msg = "Une erreur s'est produite";
                        $this->response_json(false, null, $msg);
                    }
                } else {
                    $msg = "L'utilisateur d'id: {$_GET['id']} n'existe pas";
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

    public function delete()
    {
        header('Content-Type: application/json');
        $content = file_get_contents('php://input');
        $json = json_decode($content, true);
        if (!empty($json)) {
            if ($this->Member->find($json['id'])) {
                $result = $this->Member->delete($json['id']);
                if ($result) {
                    $msg = "L'utilisateur a bien été supprimé";
                    $this->response_json(true, null, $msg);
                } else {
                    $msg = "Une erreur s'est produite";
                    $this->response_json(false, null, $msg);
                }
            } else {
                $msg = "L'offre d'id: {$json['id']} n'existe pas";
                $this->response_json(false, null, $msg);
            }
        } else {
            $msg = "Les champs sont vides";
            $this->response_json(false, null, $msg);
        }
    }

    public function candidate(){
        if(AppFactory::getInstance()->getSession()->verifySession("user")){
            $data = json_decode(file_get_contents("php://input"),true);
            $token = AppFactory::getInstance()->getSession()->getToken();
            $statusAppliant = $this->Member->getApplicationStatus($data["id"], $token);
            if(!$statusAppliant){
                if($this->Member->candidate($data) && $this->Appliant->apply($data)) $this->response_json(true,null,"You are now appliant");
                else $this->response_json(false,null,"unexpected error");
            }
            else $this->response_json(false,null,"Already appliant");
        }
    }

    public function isAppliant($id = -1)
    {
        if(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])) $id = $_GET["id"];
        if (AppFactory::getInstance()->getSession()->verifySession("user")) {
            $token = AppFactory::getInstance()->getSession()->getToken();
            $statusAppliant = $this->Member->getApplicationStatus($_GET["id"], $token);
            $this->response_json(true, $statusAppliant,$token);
        }
        else $this->response_json(false,null,"invalid session");
    }
}