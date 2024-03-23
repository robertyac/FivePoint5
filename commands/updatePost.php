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
    $postImageContent = $postImage !== null ? file_get_contents($postImage) : null;

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

    // Redirect to index.php with success message
    header("Location: ../index.php?success=Post updated successfully.");
    exit();
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
?>