<?php
session_start(); // Start the session

function getPostByID($postID) {
    $config = require '../commands/config.php';

    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass);

        $sql = "SELECT Post.PostID, Post.PostTitle, Post.PostImage, Post.Description, Post.PostDateTime, GROUP_CONCAT(Tag.Name) AS Tags
                FROM Post
                LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
                LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
                WHERE Post.PostID = ?
                GROUP BY Post.PostID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$postID]);

        $post = $stmt->fetch();

        if (!$post) {
            echo "No post found";
            return null;
        }

        $_SESSION['postID'] = $post['PostID']; // Set the post ID in the session

        return $post;
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>