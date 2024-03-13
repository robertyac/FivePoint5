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

    $sql = "SELECT Post.PostID, Post.PostTitle, Post.PostImage, Post.Description, Post.PostDateTime, Post.Rating, GROUP_CONCAT(Tag.Name) AS Tags
            FROM Post
            LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
            LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
            GROUP BY Post.PostID";
    $stmt = $pdo->query($sql);

    $posts = $stmt->fetchAll();

    if (!$posts) {
        echo "No posts found";
    }

    // return the posts array used in index.php 
    return $posts;
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
?>