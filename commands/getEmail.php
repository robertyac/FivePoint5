<?php

function getEmail($username) {
    $config = require 'config.php';
    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass);

        // SQL query to get the posts with the given search term and tag
        $sql = "SELECT Email FROM User WHERE Username = :username";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $emails = $stmt->fetchAll();
        if (!$emails) {
            echo "<h1>Email not found :(</h1>";
            echo "<button class='btn btn-warning'><a href='index.php' style='color: inherit; text-decoration: none;'>Go back</a></button>";
            die();
        }
        return $emails[0]['Email'];
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}