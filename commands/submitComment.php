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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postID = $_SESSION['postID']; // Get the postID from the session
        $userID = $_SESSION['user_id']; // Get the userID from the session
        $content = $_POST['content'];

        $sql = "INSERT INTO Comment (PostID, UserID, Content) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$postID, $userID, $content]);

        // Redirect back to the post page after successfully adding the comment
        header("Location: ../viewPost.php?PostID=$postID#commentForm");
        exit;
    }
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
?>