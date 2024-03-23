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

$commentID = $_POST['CommentID'];

// Fetch the comment from the database
$query = "SELECT * FROM Comment WHERE CommentID = ?";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $commentID, PDO::PARAM_INT);
$stmt->execute();
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the comment exists and if the user is the author or an admin
if ($comment && ($_SESSION['Username'] == $comment['Username'] || $_SESSION['IsAdmin'])) {
    $query = "DELETE FROM Comment WHERE CommentID = ?";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(1, $commentID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: ../viewPost.php?PostID=' . $_POST['PostID']);
    } else {
        die('Failed to delete comment');
    }
} else {
    die('You do not have permission to delete this comment');
}
?>