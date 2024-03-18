<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../commands/getPost.php';

$postTitle = $_POST['postTitle'];
$postDescription = $_POST['postDescription'];
$postImage = null; // Initialize $postImage to null

// Handle the file upload
if (isset($_FILES['postImage'])) {
    $errors = array();
    $file_tmp = $_FILES['postImage']['tmp_name'];
    $file_type = $_FILES['postImage']['type'];
    $file_size = $_FILES['postImage']['size'];

    // Check file size (2MB maximum)
    if ($file_size > 2097152) {
        $errors[] = 'File size must not be larger than 2 MB';
    }

    // Check file type
    if ($file_type != 'image/jpeg' && $file_type != 'image/png') {
        $errors[] = 'Only JPEG and PNG images are allowed';
    }

    if (empty($errors)) {
        // Read the file content
        $postImage = file_get_contents($file_tmp);
    } else {
        echo "File upload errors:\n";
        print_r($errors);
        return;
    }
}

try {
    echo "Attempting to insert into database.\n";
    $sql = "INSERT INTO Post (PostTitle, PostImage, Description) VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $postTitle);
    $stmt->bindParam(2, $postImage, PDO::PARAM_LOB);
    $stmt->bindParam(3, $postDescription);
    $result = $stmt->execute();

    if ($result === false) {
        echo "Database insert failed.\n";
        print_r($stmt->errorInfo());
        return;
    }

    echo "New post created successfully\n";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>