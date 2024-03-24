<?php
function getRatedPosts($username) {
    $config = require 'config.php';
    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        prepUserId($username);
    }
    try {
        $pdo = new PDO($dsn, $user, $pass);

        // SQL query to get the posts with the given search term and tag
        $sql = "SELECT Post.PostID, Post.PostTitle, Post.PostImage, Post.Description, Post.PostDateTime, Post.UserID, GROUP_CONCAT(DISTINCT Tag.Name) AS Tags, IFNULL(ROUND(AVG(UserRatings.Rating), 1), 0) AS AverageRating
        FROM (
            SELECT Post.PostID
            FROM Post
            LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
            LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
            LEFT JOIN UserRatings ON Post.PostID = UserRatings.PostID
            WHERE (UserRatings.UserID = :userID)
            GROUP BY Post.PostID
        ) AS FilteredPosts
        LEFT JOIN Post ON FilteredPosts.PostID = Post.PostID
        LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
        LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
        LEFT JOIN UserRatings ON Post.PostID = UserRatings.PostID
        GROUP BY Post.PostID";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userID' => $_SESSION['user_id']]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}