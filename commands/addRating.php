<?php
session_start(); // Start the session

$config = require 'config.php';
$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $rating = round($_POST["rating"] / 10, 1);
        $userID = $_SESSION["user_id"] ?? null;
        $postID = $_SESSION["postID"] ?? null;

        // Check if the user is logged in
        if ($userID === null) {
            // Redirect to viewPost.php with error message
            header("Location: ../viewPost.php?PostID=$postID&error=You must be logged in to rate a post.");
            exit();
        }

        echo "Rating: ";
        var_dump($rating);
        echo "User ID: ";
        var_dump($userID);
        echo "Post ID: ";
        var_dump($postID);

        // Check if a rating already exists for this user and post
        $sql = "SELECT * FROM UserRatings WHERE UserID = ? AND PostID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userID, $postID]);
        $result = $stmt->fetchAll();

        if (count($result) > 0) {
            // Update existing rating
            $sql = "UPDATE UserRatings SET Rating = ? WHERE UserID = ? AND PostID = ?";
        } else {
            // Insert new rating
            $sql = "INSERT INTO UserRatings (Rating, UserID, PostID) VALUES (?, ?, ?)";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$rating, $userID, $postID]);

        echo "Rating updated successfully";

        // Redirect back to viewPost.php
        header("Location: ../viewPost.php?PostID=$postID");
        exit();
    } else {
        echo "Invalid request";
    }
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
?>