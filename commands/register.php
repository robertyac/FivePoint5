<?php
$config = require 'config.php';

$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . ". Error code: " . $mysqli->connect_errno);
}
echo var_dump($_POST);
echo "<br>";
echo var_dump($_FILES);
$password = $_POST['passwordInput'];
$hash = hash('sha256', $password);

if (isset($_FILES['profilePicInput']) && $_FILES['profilePicInput']['error'] === UPLOAD_ERR_OK) {
    $imagePath = $_FILES['profilePicInput']['tmp_name'];
    echo 'pic found ' . $imagePath;
} else {
    echo "<script>console.log('no pic found');</script>";
    $imagePath = "../img/defaultProfilePic.png";
}
$profilePicture = file_get_contents($imagePath);

$sql = "INSERT INTO User (Username, Email, PasswordHash, ProfilePicture)
                VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ssss', $_POST['usernameInput'], $_POST['emailInput'], $hash, $profilePicture);
$stmt->execute();


echo "User added successfully";
// close the connection
$mysqli->close();
session_start();
$_SESSION['user'] = $_POST['usernameInput'];
// redirect to index.php
header('Location: ../index.php');