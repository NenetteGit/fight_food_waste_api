<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 02/04/2019
 * Time: 18:16
 */

namespace Core\Controllers;

class Controller
{
    public static function response_json($success, $data, $msg = null) {
        $response['success'] = $success;
        $response['message'] = $msg;
        $response['result'] = $data;
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}