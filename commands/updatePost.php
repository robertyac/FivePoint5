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

    $userID = $_SESSION['user_id'] ?? null;
    $postID = $_POST['postID'] ?? null;
    $postTitle = $_POST['PostTitle'] ?? null;
    $postDescription = $_POST['Description'] ?? null;
    $postImage = $_FILES['PostImage']['tmp_name'] ?? null;
    $existingImagePath = $_POST['existingImagePath'] ?? null;
    $postImageContent = null;

    // Check if the user is logged in
    if ($userID === null) {
        $_SESSION['error'] = "You must be logged in to update a post.";
        header("Location: ../editPost.php?PostID={$postID}");
        exit();
    }

    // Check if title is provided and its length is between 1 and 75 characters
    if ($postTitle === null || trim($postTitle) === '' || strlen($postTitle) < 1 || strlen($postTitle) > 75) {
        $_SESSION['error'] = "Title is required and must be between 1 and 75 characters.";
        header("Location: ../editPost.php?PostID={$postID}");
        exit();
    }

    // Check if either image or description is provided
    if ((!isset($_FILES['PostImage']) || $_FILES['PostImage']['tmp_name'] === '') && 
        ($postDescription === null || trim($postDescription) === '')) {
        $_SESSION['error'] = "Either image or description is required.";
        header("Location: ../editPost.php?PostID={$postID}");
        exit();
    }

    // If description is provided, check its length is between 1 and 1000 characters
    if ($postDescription !== null && trim($postDescription) !== '' && (strlen($postDescription) < 1 || strlen($postDescription) > 1000)) {
        $_SESSION['error'] = "Description must be between 1 and 1000 characters.";
        header("Location: ../editPost.php?PostID={$postID}");
        exit();
    }

    // Handle the file upload
    if (isset($_FILES['PostImage']) && $_FILES['PostImage']['tmp_name'] != '') {
        // Check if the file is an image
        $check = getimagesize($_FILES["PostImage"]["tmp_name"]);
        if($check === false) {
            $_SESSION['error'] = "File is not an image.";
            header("Location: ../editPost.php?postID={$postID}");
            exit();
        }

        // Read the file
        $postImageContent = file_get_contents($_FILES['PostImage']['tmp_name']);
    } elseif ($existingImagePath && file_exists($existingImagePath)) {
        $postImageContent = file_get_contents($existingImagePath);
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
            // Validate the length of the tag
            if (strlen($tag) < 1 || strlen($tag) > 12) {
                $_SESSION['error'] = "Each tag must be between 1 and 12 characters.";
                header("Location: ../editPost.php?PostID={$postID}");
                exit();
            }

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