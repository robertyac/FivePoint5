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
$newEmail = $_POST['newEmail'];

$sql = "UPDATE User SET Email = ? WHERE Username = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ss', $newEmail, $username);
$stmt->execute();
// close the connection
$mysqli->close();

// redirect to index.php
header('Location: ../profile.php');

echo "Email updated successfully, failed to redirect";