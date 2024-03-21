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

// Add reCAPTCHA validation here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $recaptchaResponse);
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        // reCAPTCHA validation failed, handle accordingly
        session_start();
        $_SESSION['alert'] = "reCAPTCHA validation failed, please try again.";
        header('Location: ../index.php');
        exit;
    }
}

echo var_dump($_POST);
echo "<br>";
echo var_dump($_FILES);
$username = $_POST['usernameInput'];
$email = $_POST['emailInput'];
$password = $_POST['passwordInput'];
$hash = hash('sha256', $password);

$check = testIfUsernameOrEmailIsTaken($username, $email);
if ($check != "") {
    echo '<script>console.log("' . $check . '")</script>';
    echo $check;
    session_start();
    $_SESSION['alert'] = $check;
    header('Location: ../index.php');
} else {
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
    $stmt->bind_param('ssss', $username, $email, $hash, $profilePicture);
    $stmt->execute();

    echo "User added successfully";
// close the connection
    $mysqli->close();
    session_start();
    $_SESSION['user'] = $_POST['usernameInput'];
// redirect to index.php
    header('Location: ../index.php');
}


function testIfUsernameOrEmailIsTaken($username, $email) {
    try {
        $config = require 'config.php';
        $host = $config['database']['host'];
        $db = $config['database']['name'];
        $user = $config['database']['user'];
        $pass = $config['database']['password'];
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $pdo = new PDO($dsn, $user, $pass);

        // SQL query to get the posts with the given search term and tag
        $sql = "SELECT Username, Email FROM User WHERE Username = :username OR Email = :email";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username, 'email' => $email]);
        $results = $stmt->fetchAll();
        if ($results) {
            if ($results[0]['Username'] == $username) {
                return "Username already in use. Try logging in";
            } else {
                return "Email already taken. Try logging in";
            }
        }
        return "";
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}