<?php
$config = require 'config.php';
$host = $config['database']['host'];
$db = $config['database']['name'];
$user = $config['database']['user'];
$pass = $config['database']['password'];
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$searchTerm = $_GET['search'] ?? ''; // Get the search term from the query parameters
$tag = $_GET['tag'] ?? ''; // Get the tag from the query parameters

try {
    $pdo = new PDO($dsn, $user, $pass);

    // SQL query to get the posts with the given search term and tag
    $sql = "SELECT Post.PostID, Post.PostTitle, Post.PostImage, Post.Description, Post.PostDateTime, Post.UserID, GROUP_CONCAT(DISTINCT Tag.Name) AS Tags, IFNULL(ROUND(AVG(UserRatings.Rating), 1), 0) AS AverageRating
    FROM (
        SELECT Post.PostID
        FROM Post
        LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
        LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
        WHERE (Post.PostTitle LIKE :searchTerm OR Post.Description LIKE :searchTerm) AND (Tag.Name = :tag OR :tag = '')
        GROUP BY Post.PostID
    ) AS FilteredPosts
    LEFT JOIN Post ON FilteredPosts.PostID = Post.PostID
    LEFT JOIN PostTags ON Post.PostID = PostTags.PostID
    LEFT JOIN Tag ON PostTags.TagID = Tag.TagID
    LEFT JOIN UserRatings ON Post.PostID = UserRatings.PostID
    GROUP BY Post.PostID";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['searchTerm' => "%$searchTerm%", 'tag' => $tag]);
    $posts = $stmt->fetchAll();
    if (!$posts) {
?>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN' crossorigin='anonymous'>

        <div class='modal' tabindex='-1' role='dialog' id='modalAlert'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Sorry about that!</h5>
                    </div>
                    <div class='modal-body'>
                        <p>No posts were found for that search :( <br /> You will now be redirected.</p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' onclick='redirect()'>OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js' integrity='sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL' crossorigin='anonymous'></script>
        <!-- jquery -->
        <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>

        <script type='text/javascript'>
            function redirect() {
                window.location.href = 'index.php';
            }

            $(document).ready(function() {
                $('#modalAlert').modal('show');
            });
        </script>
<?php
        die();
    }
    // return the posts array used in index.php 
    return $posts;
} catch (PDOException $e) {
    die("PDO error: " . $e->getMessage());
}
?>