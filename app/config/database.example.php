<?php

$host = '127.0.0.1';
$port = '3306';
$database = 'helpdesk_ticketing';
$username = 'ISI_USERNAME_MYSQL';
$password = 'ISI_PASSWORD_MYSQL';

$dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);