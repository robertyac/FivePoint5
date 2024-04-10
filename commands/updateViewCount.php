<?php
function updateViewCount($postID) {
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

        if ($viewCount !== false) {
            // Increment the viewCount by 1
            $viewCount++;

            // Prepare a SQL statement to update the viewCount in the PostViews table
            $stmt = $pdo->prepare("UPDATE PostViews SET viewCount = ? WHERE postID = ?");
            $stmt->execute([$viewCount, $postID]);
        } else {
            // Prepare a SQL statement to insert a new row in the PostViews table
            $stmt = $pdo->prepare("INSERT INTO PostViews (postID, viewCount) VALUES (?, 1)");
            $stmt->execute([$postID]);
        }
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>