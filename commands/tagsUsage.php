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

        $sql = "SELECT Tag.Name, COUNT(PostTags.TagID) as Count FROM Tag 
                JOIN PostTags ON Tag.TagID = PostTags.TagID 
                JOIN Post ON PostTags.PostID = Post.PostID 
                JOIN User ON Post.UserID = User.UserID";

        // Get the search term from the query parameters
        $searchTerm = $_GET['search'] ?? '';

        $params = [];
        // If there's a search term, append a WHERE clause to the SQL query
        if ($searchTerm !== '') {
            $sql .= " WHERE Post.PostID LIKE :searchTerm OR User.Username LIKE :searchTerm OR User.Email LIKE :searchTerm";
            $params['searchTerm'] = "%$searchTerm%";
        }

        $sql .= " GROUP BY Tag.Name";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $tags ? array_column($tags, 'Count', 'Name') : [];
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>