<?php
$host = 'localhost';
$db = 'social';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT Post.PostID, Post.PostTitle, Post.PostImage, Post.Description, Post.PostDateTime, Post.Rating, GROUP_CONCAT(Tag.Name) AS Tags
        FROM Post
        LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
        LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
        GROUP BY Post.PostID";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // change the href to the correct path to post template
        include '/Users/robertyacovelli/cosc360_proj/templates/post.php';
    }
} else {
    echo "No posts found";
}
$mysqli->close();
?>