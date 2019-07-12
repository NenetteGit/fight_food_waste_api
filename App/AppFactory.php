<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 26/03/2019
 * Time: 23:00
 */


use App\Controllers\AppController;
use Core\Config;
use Core\Database\MySqlDatabase;
use App\Controllers\SessionsController;

class AppFactory
{
    public $title = 'Fight Food Waste API';
    private $db_instance;
    private static $session_instance;
    private static $_instance;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new AppFactory();
        }
        return self::$_instance;
    }

    public static function load()
    {
        require ROOT . '/App/Autoloader.php';
        App\Autoloader::register();
        require ROOT . '/Core/Autoloader.php';
        Core\Autoloader::register();
    }

    public function getTable($name) {
        $className = '\\App\\Table\\' . ucfirst($name) . 'Table';
        return new $className($this->getDb());
    }

    public function getDb()
    {
        $config = Config::getInstance(ROOT . '/config/config.php');
        if ($this->db_instance === null) {
            $this->db_instance = new MySqlDatabase(
                $config->get('db_name'),
                $config->get('db_user'),
                $config->get('db_pass'),
                $config->get('db_host'),
                $config->get('db_port')
            );
        }
        return $this->db_instance;
    }

    public function getSession(){
        if(self::$session_instance === null){
            self::$session_instance = new SessionsController();
        }
        return self::$session_instance;
    }

    public function getKey(){
        $config = Config::getInstance(ROOT.'/config/config.php');
        return $config->get('key');
    }

    public static function forbidden()
    {
        AppController::response_json(false,"Unauthorized Access");
    }

    public static function notFound()
    {
        http_response_code(404);
        die("404 not found");
    }
}