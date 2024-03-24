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
if (!isset($_SESSION)) {
    session_start();
}

$username = $_SESSION['user'];

if (!isset($_FILES['newProfilePic']) || $_FILES['newProfilePic']['error'] !== UPLOAD_ERR_OK) {
    echo 'no pic found';
}

$imagePath = $_FILES['newProfilePic']['tmp_name'];
echo 'pic found ' . $imagePath;
$profilePicture = file_get_contents($imagePath);

$sql = "UPDATE User SET ProfilePicture = ? WHERE Username = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ss', $profilePicture, $username);
$stmt->execute();

echo "image updated successfully";
// close the connection
$mysqli->close();
// redirect to index.php
header('Location: ../profile.php');