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

// reCAPTCHA server side validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $recaptchaResponse);
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        // reCAPTCHA validation failed
        session_start();
        $_SESSION['alert'] = "reCAPTCHA validation failed, please try again.";
        header('Location: ../index.php');
        exit;
    }
}

$username = $_POST['usernameInputLogin'];

$password = $_POST['passwordInputLogin'];
$hash = hash('sha256', $password);

$sql = "SELECT * FROM User WHERE Username = '" . $username . "'";
$result = $mysqli->query($sql);

if ($result->num_rows == 0) {
    echo "user not found";
    session_start();
    $_SESSION['alert'] = "User not found";
    header('Location: ../index.php');
} else if ($result->num_rows > 1) {
    echo "multiple users found somehow";
} else {
    $row = $result->fetch_assoc();
    if ($row['PasswordHash'] == $hash) {
        echo "Login successful";
        session_start();
        $_SESSION['user'] = $username;
        $_SESSION['user_id'] = $row['UserID'];
        $_SESSION['IsAdmin'] = $row['IsAdmin'];
        header('Location: ../index.php');
    } else {
        echo "Incorrect password";;
        session_start();
        $_SESSION['alert'] = "Incorrect password";
        header('Location: ../index.php');
    }
}
$mysqli->close();