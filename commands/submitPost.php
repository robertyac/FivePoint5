<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../commands/getPost.php';

$postTitle = $_POST['postTitle'] ?? null;
$postDescription = $_POST['postDescription'] ?? null;

// Check if title is provided
if ($postTitle === null || trim($postTitle) === '') {
    // Redirect to index.php with error message
    header("Location: ../index.php?error=Title is required.");
    exit();
}

// Check if either image or description is provided
if ((!isset($_FILES['postImage']) || $_FILES['postImage']['tmp_name'] === '') && 
    ($postDescription === null || trim($postDescription) === '')) {
    // Redirect to index.php with error message
    header("Location: ../index.php?error=Either image or description is required.");
    exit();
}

// Handle the file upload
if (isset($_FILES['postImage']) && $_FILES['postImage']['tmp_name'] != '') {
    // Read the file
    $postImage = fopen($_FILES['postImage']['tmp_name'], 'rb');
} else {
    $postImage = null;
}

try {
    if ($postImage !== null) {
        $sql = "INSERT INTO Post (PostTitle, PostImage, Description) VALUES (?, ?, ?)";
    } else {
        $sql = "INSERT INTO Post (PostTitle, Description) VALUES (?, ?)";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $postTitle);

    if ($postImage !== null) {
        $stmt->bindParam(2, $postImage, PDO::PARAM_LOB);
        $stmt->bindParam(3, $postDescription);
    } else {
        $stmt->bindParam(2, $postDescription);
    }

    $result = $stmt->execute();

    if ($result === false) {
        // Redirect to index.php with error message
        header("Location: ../index.php?error=Database insert failed.");
        exit();
    }

    // Redirect to index.php with success message
    header("Location: ../index.php?success=New post created successfully.");
    exit();

} catch (PDOException $e) {
    // Redirect to index.php with error message
    header("Location: ../index.php?error=Database error: " . urlencode($e->getMessage()));
    exit();
}

// Close the file
if ($postImage !== null) {
    fclose($postImage);
}
?>