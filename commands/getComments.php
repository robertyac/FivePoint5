<?php
// Get the database configuration
$config = require 'config.php';

$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$pdo = new PDO($dsn, $user, $pass);

$postID = $_GET['postID'];
$userID = $_GET['userID'];

$comments = getComments($pdo, $postID, $userID);

// Loop through the comments
foreach ($comments as $comment) {
    // Check if the Username and Content keys exist in the $comment array
    if (isset($comment['Username']) && isset($comment['Content'])) {
        // Display a card for each comment
        echo '
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">' . htmlspecialchars($comment['Username']) . '</h5>
                <p class="card-text">' . htmlspecialchars($comment['Content']) . '</p>
            </div>
        </div>
        ';
    }
}

function getComments($pdo, $postID, $userID) {
    try {
        // SQL query to select the comments and the username of the user who made the comment
        $sql = "SELECT Comment.*, User.Username FROM Comment INNER JOIN User ON Comment.UserID = User.UserID WHERE PostID = ? AND Comment.UserID = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$postID, $userID]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}
?>