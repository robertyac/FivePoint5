<?php
session_start();

// Include getPostByID.php, getTags.php, and getAverageRating.php
include 'commands/getPostByID.php';
include 'commands/getTags.php';
include 'commands/getRating.php';

// Check if PostID is set in the URL parameters
if (!isset($_GET['PostID'])) {
    die('PostID is not set');
}

$postID = $_GET['PostID'];

// Fetch the post details using getPostByID.php
$post = getPostByID($postID);


if (!$post) {
    die('Post not found');
}

// Fetch the tags for the post using getTags.php
$tags = getTags($postID);

if (!$tags) {
    die('No tags found for this post');
}

// Fetch the average rating for the post using getAverageRating.php
$averageRating = getAverageRating($postID);

if (!$averageRating) {
    die('No ratings found for this post');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="post.css" />
    <title>View Post</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-secondary">
    <!--Navigation bar-->
    <div id="nav" style="height: 100px;"><?php include 'display_elements/nav.php'; ?></div>
    <!-- Login modal -->
    <?php include 'display_elements/login_modal.php'; ?>

    <!-- Post Container -->
    <div class="container card mt-5 mx-auto w-75">
        <div class="row">
            <div class="col-md-12">
                <div class="card-body d-flex flex-column">
                    <!-- Post Title -->
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-0"><?php echo $post['PostTitle']; ?></h4>
                        <div class="d-flex">
                            <!-- Edit button for post authors -->
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['UserID']) : ?>
                                <div style="margin-right: 5px;">
                                    <form action="editPost.php" method="get">
                                        <input type="hidden" name="PostID" value="<?php echo $post['PostID']; ?>">
                                        <button type="submit" class="btn btn-warning">Edit Post</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                            <!-- Delete button for admins and post author -->
                            <?php if ((isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['UserID']) || (isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin'])) : ?>
                                <form action="commands/deletePost.php" method="post">
                                    <input type="hidden" name="PostID" value="<?php echo $post['PostID']; ?>">
                                    <button type="submit" class="btn btn-danger">Delete Post</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr>
                    <!-- Post Image -->
                    <div class="col-12 col-md-8 mx-auto d-flex justify-content-center">
                        <?php if (!empty($post['PostImage'])) : ?>
                            <img src="data:image/png;base64,<?php echo base64_encode($post['PostImage']); ?>" alt="Post Image" class="img-fluid p-3">
                            <hr>
                        <?php endif; ?>
                    </div>
                    <!-- Post Description -->
                    <p class="card-text text-justify mt-auto p-3 bg-light border rounded">
                        <?php echo $post['Description']; ?>
                    </p>
                    <hr>
                    <!-- Tags -->
                    <h5 class="mb-0">Tags</h5>
                    <div class="card-body d-flex flex-wrap  align-items-center">
                        <span class="text-start h6 text-body-emphasis opacity-75 rounded">
                            <?php
                            $tags = isset($post['Tags']) ? $post['Tags'] : '';
                            foreach (explode(',', $tags) as $tag) : ?>
                                <span class="badge bg-primary text-white m-1 rounded-pill"><?php echo $tag; ?></span>
                            <?php endforeach; ?>
                        </span>
                    </div>
                    <hr>
                    <h4 class="text-center">This post is rated: <?php echo $averageRating; ?>/5.5</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Post Container -->

    <!-- Rating Slider -->
    <?php
    require 'commands/getUserRating.php';

    $postID = $_GET['PostID'];
    $userID = $_SESSION['user_id'];

    $rating = getUserRating($postID, $userID);
    $ratingValue = $rating !== '?' ? $rating * 10 : 27.5;
    ?>
    <div class="container card p-3 mx-auto mt-4 mb-0 w-75">
        <label for="rating" class="mb-3">
            <h3 id="ratingDisplay">Your Rating: <?php echo $rating; ?>/5.5</h3>
        </label>
        <form method="post" action="commands/addRating.php" id="ratingForm">
            <input type="range" class="form-range" id="rating" name="rating" value="<?php echo $ratingValue; ?>" min="0" max="55" step="1" oninput="updateRatingDisplay(this.value)">
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">Submit Rating</button>
            </div>
        </form>
    </div>
    <!-- End of Rating Slider -->

    <!-- Discussion Area -->
    <div class="container card w-75 p-3 mt-4 mb-5 mx-auto">
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-3">Discussion</h3>

                <!-- Comments -->
                <div id="comments" class="mt-3"></div>
                <!-- Comments will be loaded here using Ajax -->

                <!-- Comment Form -->
                <form action="commands/submitComment.php" method="POST" id="commentForm">
                    <input type="hidden" name="postID" value="<?php echo $postID; ?>">
                    <div class="mb-3 mt-5">
                        <label for="comment">Leave a Comment:</label>
                        <textarea class="form-control" id="comment" name="content" rows="3"></textarea>
                    </div>
                    <p id="charCount"></p>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Discussion Area -->

    <!-- Ajax -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {
            $("#nav").load("../display_elements/nav.php");
        });
    </script>
    <script>
        // Update the rating display when the slider is moved
        function updateRatingDisplay(value) {
            const ratingDisplay = document.getElementById('ratingDisplay');
            const rating = value / 10;
            ratingDisplay.textContent = `Rating: ${rating.toFixed(1)}/5.5`;
        }
    </script>
    <script>
        // Load comments using Ajax
        $(document).ready(function() {
            function loadComments() {
                var data = {
                    postID: <?php echo json_encode($_GET['PostID']); ?>
                };
                <?php if (isset($_SESSION['user_id'])) : ?>
                    data.userID = <?php echo json_encode($_SESSION['user_id']); ?>;
                <?php endif; ?>

                $.ajax({
                    url: 'commands/getComments.php',
                    type: 'GET',
                    data: data,
                    success: function(data) {
                        $('#comments').html(data);
                    }
                });
            }

            loadComments(); // Load comments on page load

            // This will reload comments every 5 seconds. User will not have to refresh the page to see new comments
            setInterval(loadComments, 5000);

            $('#commentForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'commands/submitComment.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function() {
                        loadComments(); // Reload comments after submitting a new one
                    }
                });
            });
        });
    </script>
    <script>
        // Comments Char Count Script
        const textarea = document.getElementById('comment');
        const charCountDisplay = document.getElementById('charCount');
        const maxChars = 1000;

        textarea.addEventListener('input', function() {
            const charCount = this.value.length;

            if (charCount > maxChars) {
                this.value = this.value.slice(0, maxChars);
            }

            charCountDisplay.textContent = `Character Count: ${charCount}/${maxChars}`;
        });
    </script>
    <script>
        // Checks if user is signed in before submitting a rating
        $(document).ready(function() {
            $('#ratingForm').on('submit', function(e) {
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    e.preventDefault();
                    alert('Sign in to submit a rating.');
                <?php endif; ?>
            });
        });
    </script>
    <script>
        // Checks if user is signed in before submitting a comment
        $(document).ready(function() {
            $('#commentForm').on('submit', function(e) {
                var comment = $.trim($('#comment').val());

                // Check if the user is logged in
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    var isLoggedIn = false;
                <?php else : ?>
                    var isLoggedIn = true;
                <?php endif; ?>

                // Check if the comment is empty or if the user is not logged in
                if (!isLoggedIn || comment === '') {
                    e.preventDefault();

                    if (!isLoggedIn) {
                        alert('Sign in to submit a comment.');
                    } else {
                        alert('Comment cannot be empty.');
                    }
                }
            });
        });
    </script>
</body>

</html>