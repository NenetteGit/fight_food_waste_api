<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 11/05/19
 * Time: 21:09
 */

namespace App\Validators;


use Core\Validator\Validator;

class OrderValidator extends Validator
{
    private $creator;
    private $status;
    public function __construct()
    {
        parent::__construct();
    }

    public function verifyFields($optionnals){
        return true;
    }
}