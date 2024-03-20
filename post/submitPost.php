<?php
include '../commands/getPost.php';

$postTitle = $_POST['postTitle'];
$postDescription = $_POST['postDescription'];

// Handle the file upload
if (isset($_FILES['postImage'])) {
    echo "File upload detected.\n";

    // Read the file
    $postImage = fopen($_FILES['postImage']['tmp_name'], 'rb');

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

    // Close the file
    fclose($postImage);
} else {
    echo "No file uploaded.\n";
}
?>