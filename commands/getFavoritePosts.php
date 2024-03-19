<?php
session_start(); // Start the session

$config = require 'config.php';
$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$userId = $_SESSION['user_id'] ?? ''; // Get the user id from the session

try {
    $pdo = new PDO($dsn, $user, $pass);
    
    // SQL query to get the most recent 6 posts of the user's favorite tags
    $sql = "SELECT Post.PostID, Post.PostTitle, Post.PostImage, Post.Description, Post.PostDateTime, GROUP_CONCAT(DISTINCT Tag.Name) AS Tags, IFNULL(ROUND(AVG(UserRatings.Rating), 1), 0) AS AverageRating
    FROM (
        SELECT Post.PostID
        FROM Post
        INNER JOIN PostTags ON Post.PostID = PostTags.PostID
        WHERE PostTags.TagID IN (
            SELECT TagID
            FROM UserFavoriteTags
            WHERE UserID = :userId
        )
        ORDER BY Post.PostDateTime DESC
        LIMIT 6
    ) AS FilteredPosts
    LEFT JOIN Post ON FilteredPosts.PostID = Post.PostID
    LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
    LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
    LEFT JOIN UserRatings ON Post.PostID = UserRatings.PostID
    GROUP BY Post.PostID";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId]);
    $posts = $stmt->fetchAll();
    if (!$posts) {
        echo "<h1>Sorry, no posts that you like with your favourite tags:(</h1>";
        return []; // Return an empty array if there are no posts
    }
    // return the posts array used in index.php 
    return $posts;
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}