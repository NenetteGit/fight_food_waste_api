<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 11/05/19
 * Time: 17:23
 */
namespace App\Validators;

use Core\Validator\Validator;

class ProductValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();
    }

    public function validProduct($product){
        if(array_key_exists("name",$product) && array_key_exists("reference",$product) && array_key_exists("quantity",$product) && array_key_exists("expiration_date",$product)){
            if(!$this->validName($product["name"])) $this->errors[1] = "name incorrect";
            if(!$this->validNumberPositive($product["reference"])) $this->errors[2] = "reference incorrect";
            if(!$this->validNumberPositive($product["quantity"])) $this->errors[3] = "quantity incorrect";
            if(!$this->validExpirationDate($product["expiration_date"])) $this->errors[4] = "expiration date incorrect";
            if(!$this->validSize($product)) $this->errors[5] = "size incorrect";


            return $this->errors;
        }
        else $this->errors[0] = "some field does not exist";
    }

    private function validExpirationDate($date){
        $dateExplode = explode("-",$date);
        if(!$this->validNumberPositive($dateExplode[0]) && !$this->validNumberPositive($dateExplode[1]) && !$this->validNumberPositive($dateExplode[2])) return false;

        if(!checkdate($dateExplode[1],$dateExplode[2],$dateExplode[0])) return false;

        $currentDate = date("Y-m-d");
        if($date <= $currentDate) return false;

        return true;
    }

    protected function validNumberPositive($number){
        return parent::validNumber($number) && $number > 0;
    }

    public function validSize($product){
        if(isset($product["size"])) {
            $size = $product["size"];
            if (!isset($size) || empty($size)) return false;
            $sizeExplode = explode(" ", $size);
            if (!array_key_exists(1, $sizeExplode)) return false;
            if (!$this->validNumberPositive($sizeExplode[0]) || !$this->validName($sizeExplode[1])) return false;
            return true;
        }
        return false;
    }
}