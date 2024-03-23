<?php
session_start();

if (!$_SESSION['IsAdmin']) {
    die("Unauthorized access");
}

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

    $sql = "DELETE FROM Post WHERE PostID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$postID]);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
?>