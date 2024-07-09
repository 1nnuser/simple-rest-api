<?php

require_once 'models/User.php';
require_once 'utils/Response.php';

class UserController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getUsers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        Response::send(200, ['users' => $users]);
    }

    public function getUser($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            Response::send(200, ['user' => $user]);
        } else {
            Response::send(404, ['message' => 'Пользователь не найден']);
        }
    }

    public function updateUser($id, $data) {
        if (!isset($data['username']) || !isset($data['email'])) {
            Response::send(400, ['message' => 'Недостаточно данных']);
            return;
        }

        $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':username', $data['username']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        Response::send(200, ['message' => 'Пользователь обновлен']);
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        Response::send(204);

    }

    public function register($data) {
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            Response::send(400, ['message' => 'Недостаточно данных']);

            return;
        }
    
        // проверка уникальности данных юзера
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $data['email']);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existingUser) {
            Response::send(400, ['message' => 'Пользователь с такой почтой уже существует']);

            return;
        }
    
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
    
        // создание пользователя
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':username', $data['username']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':password', $password);
        $stmt->execute();

        // генерация JWT токена
        // $token = [
        //     'iss' => 'simple-api',
        //     'sub' => $user['id'],
        //     'iat' => time(),
        //     'exp' => time() + (60 * 60), // 1 час
        // ];
        // $jwt = JWT::encode($token, SECRET_KEY);
    
        Response::send(201, ['message' => 'Регистрация успешна']);

    }

    public function login($data) {
        if (!isset($data['email']) || !isset($data['password'])) {
            Response::send(400, ['message' => 'Недостаточно данных']);
            
            return;
        }

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $data['email']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data['password'], $user['password'])) {


            // генерация JWT токена
            // $token = [
            //     'iss' => 'simple-api',
            //     'sub' => $user['id'],
            //     'iat' => time(),
            //     'exp' => time() + (60 * 60), // 1 час
            // ];
            // $jwt = JWT::encode($token, SECRET_KEY);

            Response::send(200, ['message' => 'Авторизация успешна']);
        } else {
            Response::send(401, ['message' => 'Невалидная почта или пароль']);
        }
    }
}