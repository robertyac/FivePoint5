<?php
session_start();

$config = require 'config.php';

$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    die("Unauthorized");
}

if (!isset($_POST['userID'])) {
    die("No user ID");
}

$userID = $_POST['userID'];

$pdo = new PDO($dsn, $user, $pass);
$stmt = $pdo->prepare("SELECT IsEnabled FROM User WHERE UserID = :userID");
$stmt->execute(['userID' => $userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false) {
    die("User not found");
}
$newStatus = $user['IsEnabled'] ? 0 : 1;
$stmt = $pdo->prepare("UPDATE User SET IsEnabled = :newStatus WHERE UserID = :userID");
$stmt->execute(['newStatus' => $newStatus, 'userID' => $userID]);

header("Location: ../admin.php");