<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
include '../commands/getPost.php';

$postTitle = $_POST['postTitle'];
$postDescription = $_POST['postDescription'];
$postImage = ''; // Initialize $postImage to an empty string

// Create a new directory
$dir = '/Applications/XAMPP/xamppfiles/htdocs/cosc360_proj/images/';

// Create new directory with 744 permissions if it does not exist yet
if (!file_exists($dir)) {
    mkdir($dir, 0744);
}

// Handle the file upload
if (isset($_FILES['postImage'])) {
    echo "File upload detected.\n";
    $errors = array();

    $file_name = $_FILES['postImage']['name'];
    $file_size = $_FILES['postImage']['size'];
    $file_tmp = $_FILES['postImage']['tmp_name'];
    $file_type = $_FILES['postImage']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['postImage']['name'])));

    echo "File name: $file_name\n";
    echo "File size: $file_size\n";
    echo "File tmp: $file_tmp\n";
    echo "File type: $file_type\n";
    echo "File ext: $file_ext\n";

    if (empty($errors) == true) {
        $target_file = $dir . basename($_FILES["postImage"]["name"]);
        echo "Target file: $target_file\n";
        if (!is_dir($dir)) {
            echo "The target directory does not exist.\n";
        } else if (!is_writable($dir)) {
            echo "The script does not have write permissions to the target directory.\n";
        }
        if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["postImage"]["name"]). " has been uploaded.\n";
        } else {
            echo "Sorry, there was an error uploading your file.\n";
            return;
        }
        $postImage = $target_file;
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
    $result = $stmt->execute([$postTitle, $postImage, $postDescription]);

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