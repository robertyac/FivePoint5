<?php
session_start();

if (!isset($_POST['PostID'])) {
    die("No PostID provided");
}

$config = require 'config.php';

$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);

    // ensures it is a number and not a string or SQL injection
    $postID = filter_var($_POST['PostID'], FILTER_SANITIZE_NUMBER_INT);

    // Fetch the post from the database
    $sql = "SELECT UserID FROM Post WHERE PostID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$postID]);
    $post = $stmt->fetch();

    // Check if the user is an admin or the author of the post
    if (!$_SESSION['IsAdmin'] && $_SESSION['user_id'] != $post['UserID']) {
        die("Unauthorized access");
    }

    $sql = "DELETE FROM Post WHERE PostID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$postID]);

    header('Location: ../index.php');
    exit;
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
?>