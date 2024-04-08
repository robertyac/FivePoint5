<?php
function getAllPosts() {
    $config = require 'config.php';

    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT Post.PostID, PostViews.ViewCount AS Views FROM Post LEFT JOIN PostViews ON Post.PostID = PostViews.PostID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($posts === false) {
            die('Error: fetchAll failed');
        }

        return $posts ? array_column($posts, 'Views', 'PostID') : [];
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>