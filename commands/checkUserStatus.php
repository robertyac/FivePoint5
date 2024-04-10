<?php
function checkUserStatus($userID) {
    $config = require 'config.php';

    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass);

    $stmt = $pdo->prepare("SELECT IsEnabled FROM User WHERE UserID = :userID");
    $stmt->execute(['userID' => $userID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
        error_log("User not found: $userID");
    } elseif ($user['IsEnabled'] == 0) {
        error_log("User is disabled: $userID");
    }

    if ($user === false || $user['IsEnabled'] == 0) {
        $previousPage = $_SERVER['HTTP_REFERER'] ?? '../viewPost.php?PostID=$postID#commentForm';
        echo "<script type='text/javascript'>alert('Your account is disabled.'); window.location.href='$previousPage';</script>";
        exit();
    }
}
?>