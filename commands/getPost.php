<?php
$config = require 'config.php';
$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$searchTerm = $_GET['search'] ?? ''; // Get the search term from the query parameters
$tag = $_GET['tag'] ?? ''; // Get the tag from the query parameters

try {
    $pdo = new PDO($dsn, $user, $pass);
    
    // SQL query to get the posts with the given search term and tag
    $sql = "SELECT Post.PostID, Post.PostTitle, Post.PostImage, Post.Description, Post.PostDateTime, Post.Rating, GROUP_CONCAT(DISTINCT Tag.Name) AS Tags
    FROM (
        SELECT Post.PostID
        FROM Post
        LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
        LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
        WHERE (Post.PostTitle LIKE :searchTerm OR Post.Description LIKE :searchTerm) AND (Tag.Name = :tag OR :tag = '')
        GROUP BY Post.PostID
    ) AS FilteredPosts
    LEFT JOIN Post ON FilteredPosts.PostID = Post.PostID
    LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
    LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
    GROUP BY Post.PostID";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['searchTerm' => "%$searchTerm%", 'tag' => $tag]);
    $posts = $stmt->fetchAll();
    if (!$posts) {
        echo "No posts found";
    }
    // return the posts array used in index.php 
    return $posts;
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
