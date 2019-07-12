<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 30/03/2019
 * Time: 19:42
 */

namespace Core\Auth;

use Core\Database\MySqlDatabase;

class DBAuth
{

    private $db;

    public  function __construct(MySqlDatabase $db)
    {
        $this->db = $db;
    }

    public function login(string $email, string $password)
    {
        $user = $this->db->prepare('SELECT * FROM member WHERE email = ?', [$email], null, true);
        if ($user) {
            if(password_verify($password, $user->password)) {
                $user = get_object_vars($user);
                return $user["id"];
            }
            else return false;
        }
        return false;
    }


    public function emailExist($email)
    {
        $user = $this->db->prepare('SELECT 1 FROM member WHERE email = ?;', [$email], null, true);
        if ($user) {
            return true;
        }
        return false;
    }
}