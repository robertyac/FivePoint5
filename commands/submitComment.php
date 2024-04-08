<?php
session_start(); // Start the session
include 'checkUserStatus.php'; // Include the checkUserStatus.php file

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
        $postID = $_SESSION['postID'] ?? null;
        $userID = $_SESSION['user_id'] ?? null;

        // Check if the user is logged in
        if ($userID === null) {
            header("Location: ../viewPost.php?PostID=$postID&error=You must be logged in to comment.#commentForm");
            exit();
        }

        // Check if the user is enabled
        checkUserStatus($userID);

        $content = $_POST['content'];

        // Check if comment is provided and its length is between 1 and 1000 characters
        if ($content === null || trim($content) === '' || strlen($content) < 1 || strlen($content) > 1000) {
            header("Location: ../viewPost.php?PostID=$postID&error=Comment is required and must be between 1 and 1000 characters.#commentForm");
            exit();
        }

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