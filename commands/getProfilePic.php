<?php

function getProfilePic($username) {
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
        $sql = "SELECT ProfilePicture FROM User WHERE Username = :username";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $pictures = $stmt->fetchAll();
        if (!$pictures) {
            echo "<h1>Profile picture not found :(</h1>";
            echo "<button class='btn btn-warning'><a href='index.php' style='color: inherit; text-decoration: none;'>Go back</a></button>";
            die();
        }
//        echo "<img src='" . $pictures[0]['ProfilePicture'] . "' alt='Profile Picture' class='img-fluid rounded-circle' style='width: 100px; height: 100px;'>";
        // return the posts array used in index.php
        return $pictures[0]['ProfilePicture'];
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }

}