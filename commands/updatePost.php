<?php
session_start(); // Start the session

$config = require 'config.php';
$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);

    $postID = $_POST['postID'] ?? null;
    $postTitle = $_POST['PostTitle'] ?? null;
    $postDescription = $_POST['Description'] ?? null;
    $postImage = $_FILES['PostImage']['tmp_name'] ?? null;
    $existingImagePath = $_POST['existingImagePath'] ?? null;
    $postImageContent = null;

    if ($postImage && file_exists($postImage)) {
        $postImageContent = file_get_contents($postImage);
    } elseif ($existingImagePath && file_exists($existingImagePath)) {
        $postImageContent = file_get_contents($existingImagePath);
    }

    if ($postID === null || $postTitle === null || $postDescription === null) {
        header("Location: ../createPost.php?error=Post ID, title and description are required.");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM Post WHERE PostID = ?");
    $stmt->execute([$postID]);
    $existingPost = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($postTitle === $existingPost['PostTitle'] && $postDescription === $existingPost['Description'] && $postImageContent === $existingPost['PostImage']) {
        header("Location: ../index.php?message=No changes were made to the post.");
        exit();
    }

    if ($postImageContent !== null) {
        $sql = "UPDATE Post SET PostTitle = ?, PostImage = ?, Description = ? WHERE PostID = ?";
    } else {
        $sql = "UPDATE Post SET PostTitle = ?, Description = ? WHERE PostID = ?";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $postTitle);
    
    if ($postImageContent !== null) {
        $stmt->bindParam(2, $postImageContent, PDO::PARAM_LOB);
        $stmt->bindParam(3, $postDescription);
        $stmt->bindParam(4, $postID);
    } else {
        $stmt->bindParam(2, $postDescription);
        $stmt->bindParam(3, $postID);
    }
    
    $stmt->execute();
    
    $postTags = $_POST['hiddenTags'] ?? null;
    
    if ($postTags) {
        $postTags = explode(',', $postTags);
        
        $stmt = $pdo->prepare("DELETE FROM PostTags WHERE PostID = ?");
        $stmt->execute([$postID]);
        
        $stmtTag = $pdo->prepare("INSERT INTO Tag (Name) VALUES (?) ON DUPLICATE KEY UPDATE TagID=LAST_INSERT_ID(TagID)");
        $stmtPostTag = $pdo->prepare("INSERT INTO PostTags (PostID, TagID) VALUES (?, ?)");
        
        foreach ($postTags as $tag) {
            $stmtTag->execute([$tag]);
        
            $stmtGetTagID = $pdo->prepare("SELECT TagID FROM Tag WHERE Name = ?");
            $stmtGetTagID->execute([$tag]);
            $tagID = $stmtGetTagID->fetchColumn();
        
            $stmtPostTag->execute([$postID, $tagID]);
        }
    }
    
    header("Location: ../index.php?success=Post updated successfully.");
    exit();
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
?>