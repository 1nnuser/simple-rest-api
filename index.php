<?php

require_once 'config.php';
require_once 'utils/Response.php';
require_once 'controllers/UserController.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $controller = new UserController();
        $data = json_decode(file_get_contents('php://input'), true); // получение json
        if ($_SERVER['REQUEST_URI'] == '/users/login') { 
            $controller->login($data); 
        } 
        else if ($_SERVER['REQUEST_URI'] == '/users/register') { 
            $controller->register($data);
        }
        break;

    case 'GET':
        $controller = new UserController();
        if ($_SERVER['REQUEST_URI'] == '/users') {
            $controller->getUsers();
        } 
        else if (preg_match('/\/users\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
            $controller->getUser($matches[1]);
        }
        break;
    case 'PUT':
        $controller = new UserController();
        if (preg_match('/\/users\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
            $data = json_decode(file_get_contents('php://input'), true);
            $controller->updateUser($matches[1], $data);
        }
        break;
    case 'DELETE':
        $controller = new UserController();
        if (preg_match('/\/users\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
            $controller->deleteUser($matches[1]);
        }
        break;
    default:
        Response::send(405, 'Такого метода нет');
        break;
}