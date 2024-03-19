<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include getPost.php
include 'getPostByID.php';

// Check if PostID is set in the URL parameters
if (!isset($_GET['PostID'])) {
    die('PostID is not set');
}

$postID = $_GET['PostID'];

// Fetch the post details using getPost.php
$post = getPost($postID);

if (!$post) {
    die('Post not found');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!--Navigation bar-->
    <div id="nav" style="height: 100px;"></div>
    <!--End of Navigation bar-->
</head>

<body class="bg-secondary">
    <!-- Close button -->
    <a href="../index.php" class="btn-close m-3 fs-2 position-absolute" style="top: 60px; left: -5px;" aria-label="Close"></a>

    <!-- Post Container -->
    <div class="container card mt-5 mx-auto w-75">
        <div class="row">
            <div class="col-md-12">
                <div class="card-body d-flex flex-column">
                    <!-- Post Title -->
                    <h2 class="card-title text-center text-decoration-underline">
                        <?php echo $post['PostTitle']; ?>
                    </h2>
                    <!-- Post Image -->
                    <?php if (!empty($post['PostImage'])) : ?>
                        <img src="data:image/png;base64,<?php echo base64_encode($post['PostImage']); ?>" alt="Post Image" class="img-fluid mx-auto d-block p-5">
                    <?php else : ?>
                        <p class="text-light">
                            <?php
                            if (isset($post['Description'])) {
                                $words = explode(' ', $post['Description'] . '  ...read more');
                                echo implode(' ', array_slice($words, 0, 20)); // show first 20 words of the description if no image and description exists
                            }
                            ?>
                        </p>
                    <?php endif; ?>
                    <!-- Post Description -->
                    <p class="card-text text-justify mt-auto">
                        <?php echo $post['Description']; ?>
                    </p>
                    <!-- Tags -->
                    <div class="card-body d-flex flex-wrap justify-content-center align-items-center">
                        <span class="badge bg-primary m-2 px-5 py-2 rounded-pill">Tag1</span>
                        <span class="badge bg-primary m-2 px-5 py-2 rounded-pill">Tag2</span>
                        <span class="badge bg-primary m-2 px-5 py-2 rounded-pill">Tag3</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Post Container -->

    <!-- Rating Slider -->
    <div class="container card p-3 mx-auto mt-4 mb-0 w-75">
        <label for="rating">
            <h3 id="ratingDisplay">Rating: /5.5</h3>
        </label>
        <input type="range" class="form-range" id="rating" value="3" min="1" max="6" step="1">
        <div class="d-flex justify-content-between">
            <div>1</div>
            <div>2</div>
            <div>3</div>
            <div>4</div>
            <div>5</div>
            <div>5.5</div>
        </div>
    </div>
    <!-- End of Rating Slider -->
    
    <!-- Discussion Area -->
    <div class="container card w-75 p-3 mt-4 mb-5 mx-auto">
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-3">Discussion</h3>

                <!-- Comments -->
                <div class="mt-3">
                    <!-- Single Comment -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Name of Commenter</h5>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit 
                                esse cillum dolore eu fugiat nulla pariatur.</p>
                        </div>
                    </div>

                    <!-- Repeat the above structure for additional comments -->
                </div>

                <!-- Comment Form -->
                <form>
                    <div class="mb-3 mt-5">
                        <label for="comment">Leave a Comment:</label>
                        <textarea class="form-control" id="comment" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Discussion Area -->

    <!-- Script Tags - Place at End of Body -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            $("#nav").load("../display_elements/nav.php");
        });
    </script>
    <script>
        $(document).ready(function() {
            // Get the slider and rating display elements
            var slider = $("#rating");
            var ratingDisplay = $("#ratingDisplay");

            // Define the mapping from slider values to ratings
            var ratingValues = {
                1: '1',
                2: '2',
                3: '3',
                4: '4',
                5: '5',
                6: '5.5'
            };

            // Update the rating display when the slider value changes
            slider.on('input', function() {
                var rating = ratingValues[this.value];
                ratingDisplay.text("Rating: " + rating + "/5.5");
            });
        });
    </script>
</body>

</html>