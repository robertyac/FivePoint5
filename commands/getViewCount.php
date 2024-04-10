<?php
function getViewCount($postID) {
    $config = require 'config.php';

    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass);

        // Prepare a SQL statement to select the viewCount from the PostViews table
        $stmt = $pdo->prepare("SELECT viewCount FROM PostViews WHERE postID = ?");
        $stmt->execute([$postID]);

        // Fetch the result and store the viewCount in a variable
        $viewCount = $stmt->fetchColumn();

        return $viewCount;
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>