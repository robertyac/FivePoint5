<?php
function getUserRating($postID, $userID) {
    // Get the database configuration
    $config = require 'config.php';

    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass);

        // SQL query to get the rating of the given post by the given user
        $sql = "SELECT UserRatings.Rating
        FROM UserRatings
        WHERE UserRatings.PostID = ? AND UserRatings.UserID = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$postID, $userID]);

        $userRating = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userRating ? $userRating['Rating'] : '?';
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>