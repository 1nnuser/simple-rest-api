<?php

// инициализация конфига бд
define('DB_HOST', 'localhost');
define('DB_NAME', ''); 
define('DB_USER', ''); 
define('DB_PASSWORD', ''); 

// JWT под будущую реализацию
define('SECRET_KEY', '8d2f6b7376552be760decc00b99f7e6c9ab401cb4932232bb7922ae85cfc3945');

// конект к бд
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
