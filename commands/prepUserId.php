<?php
function prepUserId($username) {
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
        $sql = "SELECT UserID FROM User WHERE Username = :username";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $IDs = $stmt->fetchAll();
        if (!$IDs) {
            echo "<h1>User not found :(</h1>";
            echo "<button class='btn btn-warning'><a href='index.php' style='color: inherit; text-decoration: none;'>Go back</a></button>";
            die();
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $IDs[0]['UserId'];


    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }

}