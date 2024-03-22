<?php
session_start();

include '../commands/getPost.php';

$userID = $_SESSION['user_id'] ?? null;
$postTitle = $_POST['postTitle'] ?? null;
$postDescription = $_POST['postDescription'] ?? null;

// Check if the user is logged in
if ($userID === null) {
    // Redirect to createPost.php with error message
    header("Location: ../createPost.php?error=You must be logged in to create a post.");
    exit();
}

// Check if title is provided and its length is between 1 and 75 characters
if ($postTitle === null || trim($postTitle) === '' || strlen($postTitle) < 1 || strlen($postTitle) > 75) {
    // Redirect to index.php with error message
    header("Location: ../createPost.php?error=Title is required and must be between 1 and 75 characters.");
    exit();
}

// Check if either image or description is provided
if ((!isset($_FILES['postImage']) || $_FILES['postImage']['tmp_name'] === '') && 
    ($postDescription === null || trim($postDescription) === '')) {
    // Redirect to index.php with error message
    header("Location: ../createPost.php?error=Either image or description is required.");
    exit();
} else

// If description is provided, check its length is between 1 and 1000 characters
if ($postDescription !== null && trim($postDescription) !== '' && (strlen($postDescription) < 1 || strlen($postDescription) > 1000)) {
    // Redirect to createPost.php with error message
    header("Location: ../createPost.php?error=Description must be between 1 and 1000 characters.");
    exit();
}

// Handle the file upload
if (isset($_FILES['postImage']) && $_FILES['postImage']['tmp_name'] != '') {
    // Check if the file is an image
    $check = getimagesize($_FILES["postImage"]["tmp_name"]);
    if($check === false) {
        // Redirect to index.php with error message
        header("Location: ../createPost.php?error=File is not an image.");
        exit();
    }

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
        header("Location: ../createPost.php?error=Database insert failed.");
        exit();
    }

    // Redirect to index.php with success message
    header("Location: ../index.php?success=New post created successfully.");
    exit();

} catch (PDOException $e) {
    // Redirect to index.php with error message
    header("Location: ../createPost.php?error=Database error: " . urlencode($e->getMessage()));
    exit();
}

// Close the file
if ($postImage !== null) {
    fclose($postImage);
}
?>