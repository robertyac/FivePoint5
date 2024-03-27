<?php

function makeQuery($query): array {
    $config = require 'config.php';
    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $pdo = new PDO($dsn, $user, $pass);

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();

    $array = [];

    if(gettype($results) != "boolean"){
        foreach ($results as $result) {
            $array[] = $result;
        }
    }

    return $array;
}