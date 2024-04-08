<?php
function getAllTags() {
    $config = require 'config.php';

    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass);

        $sql = "SELECT Tag.Name, COUNT(PostTags.TagID) as Count FROM Tag JOIN PostTags ON Tag.TagID = PostTags.TagID GROUP BY Tag.Name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $tags ? array_column($tags, 'Count', 'Name') : [];
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>