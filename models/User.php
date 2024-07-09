<?php

// TODO
// Реализовать в будущем модель User 
// Сделать валидацию данных и методы работы с данными


class User {
    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct($id = null, $username = null, $email = null, $password = null) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }
}