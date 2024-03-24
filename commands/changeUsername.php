<?php
$config = require 'config.php';

$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$secretKey = $config['recaptcha']['secret_key'];
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . ". Error code: " . $mysqli->connect_errno);
}
if (!isset($_SESSION)){
    session_start();
}

$username = $_SESSION['user'];
$newUsername = $_POST['newUsername'];

$sql = "UPDATE User SET Username = ? WHERE Username = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ss', $newUsername, $username);
$stmt->execute();

echo "username updated successfully";
// close the connection
$mysqli->close();
$_SESSION['user'] = $newUsername;
// redirect to index.php
header('Location: ../profile.php');