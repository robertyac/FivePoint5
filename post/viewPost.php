<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="post.css" />
    <title>View Post</title>
    <link rel="icon" type="image/x-icon" href="/img/5.5.ico">
    <link rel="apple-touch-icon" href="/img/5.5-white.png">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!--Navigation bar-->
    <div id="nav" style="height: 100px;"></div>
    <!--End of Navigation bar-->
</head>

<body class="bg-secondary">
    <!-- Close button -->
    <a href="../index.html" class="btn-close m-3 fs-2 position-absolute" style="top: 60px; left: -5px;" aria-label="Close"></a>

    <!-- Post Container -->
    <div class="container card mt-5 mx-auto w-75">
        <div class="row">
            <div class="col-md-12">
                <div class="card-body d-flex flex-column">
                    <!-- Post Title -->
                    <h2 class="card-title text-center text-decoration-underline">Title Here</h2>
                    <!-- Post Image -->
                    <img src="/img/placeholder_img.webp" alt="Post Image" class="img-fluid mx-auto d-block pb-5">
                    <!-- Post Description -->
                    <p class="card-text text-justify mt-auto">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
                        nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit 
                        esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
                        sunt in culpa qui officia deserunt mollit anim id est laborum.
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
            <h3>Rating: ?/5.5</h3>
        </label>
        <input type="range" class="form-range" id="rating" value="2.75" min="0" max="5.5" step="0.5" disabled>
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-between">
                    <div>1</div>
                    <div>2</div>
                    <div>3</div>
                    <div>4</div>
                    <div>5</div>
                    <div>5.5</div>
                </div>
            </div>
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
            $("#nav").load("/nav.html");
        });
    </script>
</body>

</html>