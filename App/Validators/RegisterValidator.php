<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 11/05/19
 * Time: 18:11
 */

namespace App\Validators;

use Core\Validator\Validator;

class RegisterValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();
    }

    public function validForm($fields)
    {
        foreach ($fields as $key => $value) {
            if ($key === 'email') {
                if (!$this->validEmail($value)) $this->errors[$key] = $value;
            } elseif ($key === 'password'){
                if (!$this->validPassword($value)) $this->errors[$key] = $value;
            } elseif ($key === 'confirm'){
                if ($value !== $fields['password']) $this->errors[$key] = $value;
            } elseif ($key === 'period' || $key === 'price'){
                if (!$this->validNumber($value)) $this->errors[$key] = $value;
            } elseif ($key === 'zipcode'){
                if (!$this->validNumber(trim($value))
                    || strlen(trim($value)) > 5
                    || strlen(trim($value)) < 4) $this->errors[$key] = $value;
            } elseif ($key === 'latitude' || $key === 'longitude'){
                if (!$this->validNumber(trim($value))) $this->errors[$key] = $value;
            } elseif ($key === 'phone'){
                if (!$this->validNumber($value) || strlen($value) > 12) $this->errors[$key] = $value;
            } else if ($key === 'firstname' || $key === 'lastname') {
                if (!$this->validName($value)) $this->errors[$key] = $value;
            }
        }
        return $this->errors;
    }
}