<?php
$config = require 'config.php';
require 'prepUserId.php';

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

if(!isset($_SESSION['user_id'])){
    $_SESSION['user_id'] = getUserId($_SESSION['user']);
}

$sql = "UPDATE UserFavoriteTags SET TagId = ? WHERE UserId = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ss', $newEmail, $username);
$stmt->execute();
// close the connection
$mysqli->close();

// redirect to index.php
header('Location: ../profile.php');

echo "Email updated successfully, failed to redirect";

// Access the submitted data (assuming sent as JSON)
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['newTag'])) {
    echo "New tag: " . $data['newTag'];
}

// Optionally, return a response to the JavaScript code (e.g., success/failure)
echo json_encode(['message' => 'Term processed successfully!']);

