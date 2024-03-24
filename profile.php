<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}
include 'commands/getEmail.php';
include 'commands/getUsersPosts.php';
include 'commands/getRatedPosts.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title> Profile </title>
    <link rel="icon" type="image/x-icon" href="img/5.5.ico">
    <link rel="apple-touch-icon" href="img/5.5-white.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/profile.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/js/bootstrap.min.js"></script>
    <style>
        #sidebar {
            height: 100vh;
            /* Set height to fill the viewport */
            flex-direction: column;
        }
    </style>
    <script>
        function openPage(pageName) {
            // Hide all elements with class="tabcontent" by default */
            var i, tabcontent;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            document.getElementById(pageName).style.display = "block";
        }
    </script>
</head>
<body class="bg-body-secondary">
<div id="nav" style="height: 4em;"><?php include 'display_elements/nav.php'; ?></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-11 bg-secondary d-flex p-2 content">
            <div class="bg-light mx-auto w-50 card mt-5 h-auto" style="min-width: 400px">
                <div class="card-body mx-auto">
                    <div class="tabcontent " id="settings">
                        <div class="container">
                            <h1>Your settings:</h1>
                        </div>
                        <div class="container">
                            Change your profile picture:
                        </div>
                        <div class="container profilePicContainer m-4">
                            <div id="divImageClick" class="row border border-2 border-dark rounded me-4"
                                 style="min-height: 20em; max-width: 20em">
                                <form action="/FivePoint5/commands/changeProfilePic.php" accept="image/*" method="post"
                                      id="profileForm" enctype="multipart/form-data" novalidate>
                                    <?php
                                    $image_blob = getProfilePic($_SESSION['user']);
                                    $encoded = base64_encode($image_blob);
                                    echo '<img src="data:image/png;base64,' . $encoded . '" alt="Profile Picture"
                                                              class="img-fluid pt-3" id="imageClick"/>';
                                    ?>
                                    <div class="col-md-6 pic2"><img src="img/editIcon.svg"
                                                                    alt="Edit" id="editIcon"></div>
                                    <input type="file" id="fileInput" name="newProfilePic" style="display: none;">
                                    <input type="submit" value="Upload" style="display: none">
                                </form>
                            </div>
                        </div>
                        <div class="container">
                            <form action="/FivePoint5/commands/changeUsername.php" method="post"
                                   class="d-flex align-items-center w-50 m-4" role="form" style="min-width: 300px">
                                Username:
                                <input class="form-control ms-3" id="newUsername" type="text" value="<?php echo $_SESSION['user']; ?>"
                                       aria-label="Text" name="newUsername" required>
                            </form>
                            <form action="/FivePoint5/commands/changeEmail.php" method="post"
                                  class="d-flex align-items-center w-50 m-4" role="form" style="min-width: 300px">
                                Email:
                                <input class="form-control ms-3" id="newEmail" type="email"
                                       value="<?php echo getEmail($_SESSION['user']) ?>"
                                       aria-label="Text" name="newEmail">
                            </form>
<!--                            <form id="tag-form">-->
                                <h3>Favourite Tags</h3>
                                <ul id="tag-list" class="list-group-flush"></ul>
                                <input class="ms-4" type="text" id="new-term" placeholder="Add a new tag"><br>
<!--                                <button class="btn btn-success ms-4 mt-2"  type="submit">Update tags</button>-->
<!--                            </form>-->
                        </div>
                    </div>
                    <div class="tabcontent card-body mx-auto" style="display: none" id="posts">
                        <div class="container">
                            <h1>Your posts:</h1>
                        </div>
                        <div class="container">
                            <?php
                            $myPosts = getUsersPosts($_SESSION['user']);
                            $myPosts = array_reverse($myPosts);
                            if (!empty($myPosts)) {
                                foreach ($myPosts as $post) {
                                    include "templates/postCard.php";
                                }
                            } else{
                                echo "<div class='h5 m-4'>No posts found</div>";
                            }
                            ?>
                        </div>

                    </div>
                    <div class="tabcontent" id="commentCard" style="display: none">
                        <div class="container">
                            <h1>Comments you've made:</h1>
                        </div>
                        <div id="comments" class="mt-3"></div>
                        <!-- Ajax -->
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                        <?php include 'scripts/ajaxComments.php' ?>
                    </div>
                    <div class="tabcontent " id="rated" style="display: none">
                        <div class="container">
                            <h1>Posts you've rated:</h1>
                        </div>
                        <div class="container">
                            <?php
                            $myPosts = getRatedPosts($_SESSION['user']);
                            $myPosts = array_reverse($myPosts);
                            if (!empty($myPosts)) {
                                foreach ($myPosts as $post) {
                                    include "templates/postCard.php";
                                }
                            } else{
                                echo "<div class='h5 m-4'>No posts found</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-1 sidebar bg-dark">
            <nav class="navbar" id="sidebar">
                <ul class="nav navbar-nav">
                    <li class="nav-item mt-3">
                        <a class="nav-link tablink text-light" onclick="openPage('settings')" id="defaultOpen">
                            Settings </a>
                        <hr style="color:white;"/>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tablink text-light" onclick="openPage('posts')"> Posts </a>
                        <hr style="color:white;"/>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tablink text-light" onclick="openPage('commentCard')"> Comments </a>
                        <hr style="color:white;"/>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tablink text-light" onclick="openPage('rated')"> Rated </a>
                        <hr style="color:white;"/>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- Script Tags - Place at End of Body -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    const form = document.getElementById('profileForm');
    const fileInput = document.getElementById('fileInput');

    // Add change event listener to file input
    fileInput.addEventListener('change', function () {
        form.submit();
    });

    // Simulate click on image to trigger file selection (optional)
    const imageClick = document.getElementById('divImageClick');
    imageClick.addEventListener('click', function () {
        fileInput.click();
    });
</script>

<script>
    const termList = document.getElementById('tag-list');
    const newTermInput = document.getElementById('new-term');

    // Function to add a new term to the list
    function addTerm(term) {
        const listItem = document.createElement('li');
        listItem.classList.add('list-group-item');
        listItem.textContent = term;

        // Create and append the image for deleting
        const deleteImage = document.createElement('img');
        deleteImage.src = 'img/delete.svg';
        deleteImage.alt = 'Delete';
        deleteImage.style.height = '0.8em';
        deleteImage.classList.add('ms-2');
        deleteImage.addEventListener('click', function() {
            termList.removeChild(listItem);
        });

        listItem.appendChild(deleteImage);
        termList.appendChild(listItem);

        // Clear the input field after adding
        newTermInput.value = '';
    }

    // Add event listener to the input field for Enter key press
    newTermInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            const newTerm = newTermInput.value.trim();
            if (newTerm) {
                addTerm(newTerm);
            }
        }
    });

</script>
</body>
</html>