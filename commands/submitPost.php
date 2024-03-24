<?php
session_start();

include '../commands/getPost.php';

$userID = $_SESSION['user_id'] ?? null;
$postTitle = $_POST['postTitle'] ?? null;
$postDescription = $_POST['postDescription'] ?? null;

// Check if the user is logged in
if ($userID === null) {
    $_SESSION['error'] = "You must be logged in to create a post.";
    header("Location: ../createPost.php");
    exit();
}

// Check if title is provided and its length is between 1 and 75 characters
if ($postTitle === null || trim($postTitle) === '' || strlen($postTitle) < 1 || strlen($postTitle) > 75) {
    $_SESSION['error'] = "Title is required and must be between 1 and 75 characters.";
    header("Location: ../createPost.php");
    exit();
}

// Check if either image or description is provided
if ((!isset($_FILES['postImage']) || $_FILES['postImage']['tmp_name'] === '') && 
    ($postDescription === null || trim($postDescription) === '')) {
    $_SESSION['error'] = "Either image or description is required.";
    header("Location: ../createPost.php");
    exit();
}

// If description is provided, check its length is between 1 and 1000 characters
if ($postDescription !== null && trim($postDescription) !== '' && (strlen($postDescription) < 1 || strlen($postDescription) > 1000)) {
    $_SESSION['error'] = "Description must be between 1 and 1000 characters.";
    header("Location: ../createPost.php");
    exit();
}

// Handle the file upload
if (isset($_FILES['postImage']) && $_FILES['postImage']['tmp_name'] != '') {
    // Check if the file is an image
    $check = getimagesize($_FILES["postImage"]["tmp_name"]);
    if($check === false) {
        $_SESSION['error'] = "File is not an image.";
        header("Location: ../createPost.php");
        exit();
    }

    // Read the file
    $postImage = fopen($_FILES['postImage']['tmp_name'], 'rb');
} else {
    $postImage = null;
}

try {
    if ($postImage !== null) {
        $sql = "INSERT INTO Post (UserID, PostTitle, PostImage, Description) VALUES (?, ?, ?, ?)";
    } else {
        $sql = "INSERT INTO Post (UserID, PostTitle, Description) VALUES (?, ?, ?)";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $userID);
    $stmt->bindParam(2, $postTitle);

    if ($postImage !== null) {
        $stmt->bindParam(3, $postImage, PDO::PARAM_LOB);
        $stmt->bindParam(4, $postDescription);
    } else {
        $stmt->bindParam(3, $postDescription);
    }

    $result = $stmt->execute();

    if ($result === false) {
        // Redirect to index.php with error message
        header("Location: ../createPost.php?error=Database insert failed.");
        exit();
    }

    // Get the tags from the $_POST array
    $postTags = $_POST['hiddenTags'] ?? null;

    // Split the tags string into an array of individual tags
    $tags = array_unique(array_filter(explode(',', $postTags)));

    // Get the ID of the last inserted post
    $postID = $pdo->lastInsertId();

    foreach ($tags as $tag) {
        // Trim the tag to remove any leading or trailing whitespace
        $tag = trim($tag);

        // Check if the tag already exists in the database
        $sql = "SELECT TagID FROM Tag WHERE Name = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tag]);
        $tagID = $stmt->fetchColumn();

        // If the tag doesn't exist, insert it
        if ($tagID === false) {
            $sql = "INSERT INTO Tag (Name) VALUES (?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$tag]);
            $tagID = $pdo->lastInsertId();
        }

        // Connect the post to its tag
        $sql = "INSERT INTO PostTags (PostID, TagID) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$postID, $tagID]);
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