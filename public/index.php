<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 26/03/2019
 * Time: 17:35
 */
/** Main page : rooter */
use App\Controllers\OffersController;
use Core\Controllers\Controller;

define('ROOT', dirname(__DIR__));
require ROOT . '/App/AppFactory.php';
AppFactory::load();

if (isset($_GET['p']) && !empty($_GET['p'])) {
    $page = explode('.', $_GET['p']);

    if (isset($page) && array_key_last($page) === 1) {
        $controller = '\App\Controllers\\' . ucfirst($page[0]) . 'Controller';
        $action = $page[1];
    } elseif (isset($page) && array_key_last($page) === 2 && $page[0] === 'admin') {
        if(AppFactory::getInstance()->getSession()->verifySession("volunteer")){
            $controller = '\App\Controllers\Admin\\' . ucfirst($page[1]) . 'Controller';
            $action = $page[2];
        }
        else{
            Controller::response_json(false,null,"Unauthorized access");
            exit(0);
        }
    }
    if(isset($controller)) $controller = new $controller;
    if(isset($action) &&  method_exists($controller,$action)){
        $controller->$action();
        exit(0);
    }
    else AppFactory::notFound();
}
$controller = new OffersController();
$controller->show();