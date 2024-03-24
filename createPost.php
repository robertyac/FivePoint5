<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="post.css" />
    <title>Create a Post</title>
    <link rel="icon" type="image/x-icon" href="/img/5.5.ico">
    <link rel="apple-touch-icon" href="/img/5.5-white.png">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/post/createPost.css">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/createPost.css">
</head>

<body class="bg-secondary">
    <!--Navigation bar-->
    <div id="nav" style="height: 100px;"><?php include 'display_elements/nav.php'; ?></div>
    <!-- Login modal -->
    <?php include 'display_elements/login_modal.php'; ?>

    <!-- Post Form Container -->
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="commands/submitPost.php" enctype="multipart/form-data" onsubmit="return validateForm();">
                            <!-- Post Title -->
                            <div class="mb-3">
                                <label for="postTitle" class="form-label">Title:</label>
                                <input type="text" class="form-control" id="postTitle" name="postTitle" placeholder="Enter the post title">
                                <div id="titleCharCount"></div>
                                <div id="titleError"></div>
                            </div>

                            <!-- Post Image -->
                            <div class="mb-3">
                                <label for="postImage" class="form-label">Select an Image:</label>
                                <input type="file" class="form-control" id="postImage" name="postImage" accept=".jpg, .jpeg, .png" onchange="displayImage(this.files)" >
                                <img id="previewImage" class="img-fluid mt-2" style="display: none;" alt="Preview Image">
                            </div>

                            <!-- Post Tags -->
                            <div class="mb-3">
                                <label for="postTags" class="form-label">Tags:</label>
                                <input type="text" class="form-control" id="postTags" name="postTags" placeholder="Enter a tag">
                                <div id="tagsCharCount"></div>
                                <div id="tagsContainer" class="mt-2"></div>
                            </div>
                            <input type="hidden" id="hiddenTags" name="hiddenTags">

                            <!-- Post Description -->
                            <div class="mb-3">
                                <label for="postDescription" class="form-label">Description:</label>
                                <textarea class="form-control" id="postDescription" name="postDescription" rows="5" placeholder="Enter the post description"></textarea>
                                <p id="charCount"></p>
                                <div id="descriptionError"></div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Create Post</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Create Post Form Container -->

    <!-- Script Tags - Place at End of Body -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            $("#nav").load("/nav.html");
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
            if (title.trim().length === 0) {
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
        // Array to store tags
        var tags = [];

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
    </script>
    <script>
        // Sends tags array to hidden input field before form submission
        $('form[action="commands/submitPost.php"]').on('submit', function() {
            // Update the value of the hidden input field with the tags array
            document.getElementById('hiddenTags').value = tags.join(',');
        });
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
        // Tags Char Count Script
        const tagsInput = document.getElementById('postTags');
        const tagsCharCountDisplay = document.getElementById('tagsCharCount');
        const maxTagsChars = 12;

        tagsInput.addEventListener('input', function () {
            let charCount = this.value.length;

            if (charCount > maxTagsChars) {
                this.value = this.value.slice(0, maxTagsChars);
                charCount = maxTagsChars;
            }

            tagsCharCountDisplay.textContent = `Character Count: ${charCount}/${maxTagsChars}`;
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

</body>

</html>