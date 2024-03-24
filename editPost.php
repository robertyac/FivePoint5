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
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/createPost.css">
</head>

<body class="bg-secondary">
    <!--Navigation bar-->
    <div id="nav" style="height: 100px;"><?php include 'display_elements/nav.php'; ?></div>
    <!-- Login modal -->
    <?php include 'display_elements/login_modal.php'; ?>

    <!-- Post Container -->
    <div class="container card mt-5 mx-auto w-75">
        <form action="commands/updatePost.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body d-flex flex-column">
                        <!-- Post ID (hidden) -->
                        <input type="hidden" name="postID" value="<?php echo $postID; ?>">

                        <!-- Post Title -->
                        <h5>Title</h5>
                        <div class="d-flex justify-content-between">
                            <input type="text" class="form-control" name="PostTitle" value="<?php echo $post['PostTitle']; ?>" id="postTitle">
                        </div>
                        <div id="titleCharCount"></div>
                        <div id="titleError"></div>
                        <hr>
                        <!-- Post Image -->
                        <h5>Image</h5>
                        <div>
                            <input type="hidden" name="RemoveImage" value="0">
                            <input type="file" class="form-control" name="PostImage" id="postImage"accept="image/*" style="display: <?php echo empty($post['PostImage']) ? 'block' : 'none'; ?>;" onchange="displayImage(this.files)">
                            <?php if (!empty($post['PostImage'])) : ?>
                                <div class="d-flex flex-column align-items-center">
                                    <img id="previewImage" src="data:image/png;base64,<?php echo base64_encode($post['PostImage']); ?>" alt="Post Image" class="img-fluid p-3">
                                    <button type="button" class="btn btn-warning change-image mt-2">Change Image</button>
                                </div>
                            <?php else: ?>
                                <img id="previewImage" style="display: none;" class="img-fluid p-3">
                            <?php endif; ?>
                        </div>
                        <hr>
                        <!-- Post Description -->
                        <h5>Description</h5>
                        <textarea class="form-control" name="Description" rows="5" id="postDescription"><?php echo $post['Description']; ?></textarea>
                        <div id="charCount"></div>
                        <div id="descriptionError"></div>
                        <hr>
                        <!-- Post Tags -->
                        <div class="mb-3">
                                <label for="postTags" class="form-label">Tags:</label>
                                <input type="text" class="form-control" id="postTags" name="postTags" placeholder="Enter a tag">
                                <div id="tagsContainer" class="mt-2"></div>
                            </div>
                        <input type="hidden" id="hiddenTags" name="hiddenTags">
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
    <script>
        // Array to store tags
        var tags = [];

        var existingTags = <?php echo json_encode($tags); ?>;

        // Add existing tags to tags array
        existingTags.forEach(function(tag) {
            tags.push(tag.Name);
        });

        //handle tag input and display as Bootstrap 5 chips
        document.getElementById('postTags').addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ',') {
                event.preventDefault();
                const tag = this.value.trim();
                this.value = '';
                if (tag.length > 0 && !$("#tagsContainer .badge:contains('" + tag + "')").length) {
                    // Adding tag to array
                    tags.push(tag);

                    const chip = document.createElement('span');
                    chip.textContent = tag;
                    chip.classList.add('badge', 'bg-primary', 'badge-pill', 'me-2','p-3','mb-3');
                    chip.addEventListener('click', function() {
                        // Remove tag from array
                        const index = tags.indexOf(tag);
                        if (index > -1) {
                            tags.splice(index, 1);
                        }
                        this.remove();
                    });
                    document.getElementById('tagsContainer').appendChild(chip);
                }
            }
        });

        // Existing tags
        existingTags.forEach(function(tag) {
            const chip = document.createElement('span');
            chip.textContent = tag.Name; // Use tag.Name instead of tag
            chip.classList.add('badge', 'bg-primary', 'badge-pill', 'me-2','p-3','mb-3');
            chip.addEventListener('click', function() {
                // Remove tag from array
                const index = tags.indexOf(tag.Name); // Use tag.Name instead of tag
                if (index > -1) {
                    tags.splice(index, 1);
                }
                this.remove();
            });
            document.getElementById('tagsContainer').appendChild(chip);
        });
    </script>
    <script>
        // Sends tags array to hidden input field before form submission
        $('form[action="commands/updatePost.php"]').on('submit', function() {
            // Update the value of the hidden input field with the tags array
            document.getElementById('hiddenTags').value = tags.join(',');
        });
    </script>
    <script>
        // Function to validate the form
        function validateForm() {
            const title = document.getElementById('postTitle').value;
            const description = document.getElementById('postDescription').value;
            const image = document.getElementById('postImage').files.length;

            // Clear previous error messages
            clearError('titleError');
            clearError('descriptionError');

            let isValid = true;

            // Validate title
            console.log('Title:', title); // Debugging line
            if (title.trim().length === 0) {
                console.log('Title is empty'); // Debugging line
                displayError('titleError', 'Title is required.');
                isValid = false;
            }

            // Validate description or image
            if (description.length === 0 && image === 0) {
                displayError('descriptionError', 'Either a description or an image must be provided.');
                isValid = false;
            }

            // If validation passes, allow form to be submitted
            return isValid;
        }

        function displayError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.style.color = 'red';
        }

        function clearError(elementId) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = '';
        }
    </script>
    <script>
        // Description Char Count Script
        const textarea = document.getElementById('postDescription');
        const charCountDisplay = document.getElementById('charCount');
        const maxChars = 1000;
    
        textarea.addEventListener('input', function () {
            const charCount = this.value.length;
    
            if (charCount > maxChars) {
                this.value = this.value.slice(0, maxChars);
            }
    
            charCountDisplay.textContent = `Character Count: ${charCount}/${maxChars}`;
        });
    </script>
    <script>
        // Title Char Count Script
        const titleInput = document.getElementById('postTitle');
        const titleCharCountDisplay = document.getElementById('titleCharCount');
        const maxTitleChars = 75;

        titleInput.addEventListener('input', function () {
            let charCount = this.value.length;

            if (charCount > maxTitleChars) {
                this.value = this.value.slice(0, maxTitleChars);
                charCount = maxTitleChars;
            }

            titleCharCountDisplay.textContent = `Character Count: ${charCount}/${maxTitleChars}`;
        });
    </script>
    <script>
        // Checks if user is signed in before submitting a post
        $(document).ready(function() {
            $('form[action="commands/submitPost.php"]').on('submit', function(e) {
                <?php if (!isset($_SESSION['user_id'])): ?>
                    e.preventDefault();
                    alert('Sign in to create a post.');
                <?php endif; ?>
            });
        });
    </script>

    <!-- will show server alerts -->
    <?php if (isset($_SESSION['error'])): ?>
        <script>
            alert("<?php echo $_SESSION['error']; ?>");
        </script>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</body>

</html>