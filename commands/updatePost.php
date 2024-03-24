<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        // If a new file was uploaded, read its contents
        $postImageContent = file_get_contents($postImage);
    } elseif ($existingImagePath && file_exists($existingImagePath)) {
        // If no new file was uploaded but an existing image path is provided, read its contents
        $postImageContent = file_get_contents($existingImagePath);
    }

    // Check if post ID, post title and description are provided
    if ($postID === null || $postTitle === null || $postDescription === null) {
        // Redirect to createPost.php with error message
        header("Location: ../createPost.php?error=Post ID, title and description are required.");
        exit();
    }

    // Fetch existing post data
    $stmt = $pdo->prepare("SELECT * FROM Post WHERE PostID = ?");
    $stmt->execute([$postID]);
    $existingPost = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if new data is different from existing data
    if ($postTitle === $existingPost['PostTitle'] && $postDescription === $existingPost['Description'] && $postImageContent === $existingPost['PostImage']) {
        // Redirect to index.php with message
        header("Location: ../index.php?message=No changes were made to the post.");
        exit();
    }

    // Prepare the SQL UPDATE statement
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

    $result = $stmt->execute();

    if ($result === false) {
        // Redirect to index.php with error message
        header("Location: ../createPost.php?error=Database update failed.");
        exit();
    }

    // Retrieve the new set of tags from the form submission
    $postTags = $_POST['hiddenTags'] ?? null;

    // Convert the tags string into an array
    $postTags = explode(',', $postTags);

    // Delete the existing tags associated with the post
    $stmt = $pdo->prepare("DELETE FROM PostTags WHERE PostID = ?");
    $stmt->execute([$postID]);

    // Prepare the SQL INSERT statement for Tag table
    $stmtTag = $pdo->prepare("INSERT INTO Tag (Name) VALUES (?) ON DUPLICATE KEY UPDATE TagID=LAST_INSERT_ID(TagID)");

    // Prepare the SQL INSERT statement for PostTags table
    $stmtPostTag = $pdo->prepare("INSERT INTO PostTags (PostID, TagID) VALUES (?, LAST_INSERT_ID())");

    foreach ($postTags as $tag) {
        // Insert the tag into the Tag table and get its ID
        $stmtTag->execute([$tag]);

        // Insert the tag ID and the post ID into the PostTags table
        $stmtPostTag->execute([$postID]);
    }

    // Redirect to index.php with success message
    header("Location: ../index.php?success=Post updated successfully.");
    exit();
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
?>