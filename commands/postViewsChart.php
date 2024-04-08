<?php
function getAllPosts() {
    $config = require 'config.php';

    $host = $config['database']['host'];
    $db = $config['database']['name'];
    $user = $config['database']['user'];
    $pass = $config['database']['password'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
    $sql = "SELECT Post.PostID, PostViews.ViewCount AS Views, User.Username, User.Email FROM Post LEFT JOIN PostViews ON Post.PostID = PostViews.PostID LEFT JOIN User ON Post.UserID = User.UserID";

    // Get the search term from the query parameters
    $searchTerm = $_GET['search'] ?? '';

    $params = [];
    // If there's a search term, append a WHERE clause to the SQL query
    if ($searchTerm !== '') {
        $sql .= " WHERE Post.PostID LIKE :searchTerm OR User.Username LIKE :searchTerm OR User.Email LIKE :searchTerm";
        $params['searchTerm'] = "%$searchTerm%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($posts === false) {
            die('Error: fetchAll failed');
        }

        return $posts ? array_column($posts, 'Views', 'PostID') : [];
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>