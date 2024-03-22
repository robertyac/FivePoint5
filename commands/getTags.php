<?php
function getTags($postID) {
    // Get the database configuration
    $config = require 'config.php';

    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass);

        $sql = "SELECT Tag.Name FROM Tag JOIN PostTags ON Tag.TagID = PostTags.TagID WHERE PostTags.PostID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$postID]);

        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $tags ? $tags : ["Name" => "No tags found for this post"];
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>