<?php
function getAverageRating($postID) {
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

        // SQL query to get the average rating of the given post
        $sql = "SELECT IFNULL(ROUND(AVG(UserRatings.Rating), 1), 0) AS AverageRating
        FROM UserRatings
        WHERE UserRatings.PostID = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$postID]);

        $averageRating = $stmt->fetch(PDO::FETCH_ASSOC);

        return $averageRating ? $averageRating['AverageRating'] : 0;
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>