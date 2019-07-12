<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 27/03/2019
 * Time: 01:07
 */

namespace Core\Database;

use \PDO;

class MySqlDatabase
{

    private $dbName;
    private $dbUser;
    private $dbPwd;
    private $dbHost;
    private $dbPort;
    private $pdo;

    public function __construct($dbName, $dbUser = 'root', $dbPwd = '', $dbHost = 'localhost', $dbPort = '3306')
    {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPwd = $dbPwd;
        $this->dbHost = $dbHost;
        $this->dbPort = $dbPort;
    }

    private function getPDO()
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName;port=$this->dbPort;charset=utf8",
                $this->dbUser, $this->dbPwd);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this->pdo;
    }

    public function query($statement, $class = null, $pickOne = false)
    {
        $stmt = $this->getPDO()->query($statement);
        if (strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            return $stmt;
        }
        if ($class === null) {
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        }else {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $class);
        }
        if ($pickOne){
            return $stmt->fetch();
        }
        return $stmt->fetchAll();
    }

    public function prepare($statement, $attributes, $class = null, $pickOne = false)
    {
        $stmt = $this->getPDO()->prepare($statement);
        $res = $stmt->execute($attributes);

        if (strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            return $res;
        }
        if ($class === null) {
            $stmt->setFetchMode(PDO::FETCH_OBJ);
        }else {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $class);
        }

        if ($pickOne){
            return $stmt->fetch();
        }
        return $stmt->fetchAll();
    }

    public function lastInsertId()
    {
        return $this->getPDO()->lastInsertId();
    }
}
