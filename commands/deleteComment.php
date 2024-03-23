<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$config = require 'config.php';

$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$pdo = new PDO($dsn, $user, $pass);

if (!isset($_POST['CommentID'])) {
    die('CommentID is not set');
}

$commentID = $_POST['CommentID'];

$query = "DELETE FROM Comment WHERE CommentID = ?";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $commentID, PDO::PARAM_INT);

if ($stmt->execute()) {
    header('Location: ../viewPost.php?PostID=' . $_POST['PostID']);
} else {
    die('Failed to delete comment');
}
?>