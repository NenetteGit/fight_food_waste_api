<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 17/05/19
 * Time: 19:26
 */

namespace App\Controllers;

use AppFactory;
use Exception;

class SessionsController extends AppController
{
    public function __construct()
    {
        $this->loadModel('Member');
    }

    public function generateSession($id){
        $key = AppFactory::getInstance()->getKey();

        try {
            $token = bin2hex(random_bytes(32));
            $user = $this->Member->find($id);
            $status = $user->status;

            $jsonHeader = json_encode(["alg"=>"H256","type"=>"JWT"]);
            $jsonBody = json_encode(["token"=>$token,"status"=>$status]);

            $header = base64_encode($jsonHeader);
            $body = base64_encode($jsonBody);

            $signature = hash_hmac("sha256",$header.".".$body, $key);

            $jwt = $header.".".$body.".".$signature;

            $this->Member->updateToken($id,$token);
            return $jwt;
        } catch (Exception $e) {
            var_dump($e);
        }
        return false;
    }

    private function verifyJWT($jwt){
        $key = Appfactory::getInstance()->getKey();

        $explode = explode(".",$jwt);

        $signature = hash_hmac("sha256",$explode[0].".".$explode[1], $key);

        if($signature == $explode[2]) return true;
        return false;
    }

    public function getToken(){
        $content = file_get_contents('php://input');
        $array = json_decode($content, true);
        if (!empty($array)) {
            $jwt = $array['jwt'];
            if (isset($jwt) && $this->verifyJWT($jwt)) {
                $explode = explode(".", $jwt);
                $json = json_decode((base64_decode($explode[1])), true);
                return $json["token"];
            }
        }
        return false;
    }

    public function verifySession($checkStatus=null){
        $content = file_get_contents('php://input');
        $array = json_decode($content, true);
        if (!empty($array)) {
            $jwt = $array['jwt'];
            $id = $array["id"];
            if (isset($jwt) && isset($id) && $this->verifyJWT($jwt)) {
                $explode = explode(".", $jwt);
                $json = json_decode((base64_decode($explode[1])),true);
                if ($checkStatus === null) return $this->Member->verifyToken($id, $json["token"]);
                elseif(self::checkStatus($checkStatus,$json["status"])) return $this->Member->verifyToken($id, $json["token"]);
            }
        }
        return false;
    }

    public function verifySessionC($id,$jwt){
        if ($this->verifyJWT($jwt)) {
            $explode = explode(".", $jwt);
            $json = json_decode((base64_decode($explode[1])),true);
            return $this->Member->verifyToken($id, $json["token"]);
        }
        return false;
    }

    public static function checkStatus($status,$currentStatus){
        switch($currentStatus){
            case "admin":
                return true;
                break;
            case "employee":
                return $status === "employee" || $status === "volunteer" || $status === "user";
                break;
            case "volunteer":
                return $status === "volunteer" || $status === "user";
                break;
            case "user":
            case "appliant":
                return $status === "user" || $status === "appliant";
                break;
            default:
                return false;
        }
    }
}