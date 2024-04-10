<?php
$config = require 'config.php';

$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);

    $sql = "SELECT DISTINCT User.* FROM User LEFT JOIN Post ON User.UserID = Post.UserID";
    $params = [];

    if (isset($_GET['search']) && $_GET['search'] !== '') {
        $sql .= " WHERE User.Username LIKE :search OR User.Email LIKE :search OR Post.PostID LIKE :search";
        $params['search'] = '%' . $_GET['search'] . '%';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}

return $users;
?>