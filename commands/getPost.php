<?php
$config = require 'config.php';

$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);

echo "Before Connection";
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . ". Error code: " . $mysqli->connect_errno);
    echo "Connection failed" . $mysqli->connect_error;
}

$sql = "SELECT Post.PostID, Post.PostTitle, Post.PostImage, Post.Description, Post.PostDateTime, Post.Rating, GROUP_CONCAT(Tag.Name) AS Tags
        FROM Post
        LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
        LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
        GROUP BY Post.PostID";
$result = $mysqli->query($sql);

$posts = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
} else {
    echo "No posts found";
}
$mysqli->close();

// return the posts array to be used in index.php 
return $posts;
?>