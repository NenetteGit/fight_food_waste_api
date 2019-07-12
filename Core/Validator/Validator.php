<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 03/04/2019
 * Time: 12:45
 */

namespace Core\Validator;

class Validator
{
    protected $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    protected function validName($name)
    {
        return isset($name) && !empty($name) && !is_numeric($name);
    }

    protected function validNumber($name)
    {
        return isset($name) && !empty($name) && is_numeric($name);
    }

    protected function validEmail($email)
    {
        return isset($email)
            && !empty($email)
            && filter_var($email,FILTER_VALIDATE_EMAIL)
            && preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email);
    }

    protected function validPassword($pwd)
    {
        return isset($pwd)
            && !empty($pwd)
            && strlen($pwd) > 8
            && strlen($pwd) < 20
            && preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#',
                $pwd);
    }
}