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
    <!--Navigation bar-->
    <div id="nav" style="height: 100px;"><?php include 'display_elements/nav.php'; ?></div>
    <!--End of Navigation bar-->
</head>

<body class="bg-secondary">
    <!-- Close button -->
    <a href="index.php" class="btn-close m-3 fs-2 position-absolute" style="top: 60px; left: -5px;" aria-label="Close"></a>

    <!-- Post Container -->
    <div class="container card mt-5 mx-auto w-75">
        <form action="commands/updatePost.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body d-flex flex-column">
                        <!-- Post ID (hidden) -->
                        <input type="hidden" name="postID" value="<?php echo $postID; ?>">

                        <!-- Post Title -->
                        <h5>Title</h5>
                        <div class="d-flex justify-content-between">
                            <input type="text" class="form-control" name="PostTitle" value="<?php echo $post['PostTitle']; ?>" required>
                        </div>
                        <hr>
                        <!-- Post Image -->
                        <h5>Image</h5>
                        <div>
                            <input type="hidden" name="RemoveImage" value="0">
                            <input type="file" class="form-control" name="PostImage" accept="image/*" style="display: <?php echo empty($post['PostImage']) ? 'block' : 'none'; ?>;" onchange="displayImage(this.files)">
                            <?php if (!empty($post['PostImage'])) : ?>
                                <div class="d-flex flex-column align-items-center">
                                    <img id="previewImage" src="data:image/png;base64,<?php echo base64_encode($post['PostImage']); ?>" alt="Post Image" class="img-fluid p-3">
                                    <button type="button" class="btn btn-danger change-image mt-2">Change Image</button>
                                </div>
                            <?php else: ?>
                                <img id="previewImage" style="display: none;" class="img-fluid p-3">
                            <?php endif; ?>
                        </div>
                        <hr>
                        <!-- Post Description -->
                        <h5>Description</h5>
                        <textarea class="form-control" name="Description" rows="5" required><?php echo $post['Description']; ?></textarea>
                        <hr>
                        <!-- Tags -->
                        <h5>Tags</h5>
                        <input type="text" class="form-control" name="Tags" value="<?php echo implode(',', $tags); ?>" required>
                        <hr>
                        <!-- Save button for post authors -->
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['UserID']) : ?>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- End of Post Container -->

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
        // Function to display the selected image
        function displayImage(files) {
            const previewImage = document.getElementById('previewImage');
            const file = files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                };

                reader.readAsDataURL(file);
            }
        }

        $(document).ready(function() {
            $('.change-image').click(function() {
                $(this).prev('img').hide();
                $(this).parent().prev('input[name="PostImage"]').show();
                $(this).parent().prev().prev('input[name="RemoveImage"]').val('1');
                $(this).remove(); // This line removes the "Change Image" button
            });
        });
    </script>
</body>

</html>