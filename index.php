<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FivePoint5 - Home</title>
    <link rel="icon" type="image/x-icon" href="/img/5.5.ico">
    <link rel="apple-touch-icon" href="/img/5.5-white.png">
    <!-- this stylesheet is for different breakpoints in browsers used with Bootstrap ***** remove after ***** -->
    <link rel="stylesheet" href="css/bootstrap-breakpoint.css">
    <!-- Index stylesheet -->
    <link rel="stylesheet" href="css/index.css">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-body-secondary">
    <!--Navigation bar-->
    <div id="nav" style="height: 100px;"></div>
    <!--End of Navigation bar-->
    <!--Briefing Section -->
    <section class=" text-center text-lg-start">
        <div class="container p-3">
            <div class="d-lg-flex align-items-center justify-content-between">
                <h1>Your 5<span class="text-primary">.</span>5 Briefing</h1>
                <p class="lead my-2">Catch up on recent posts with your favourite tags.</p>
                <a href=""><button class="btn btn-primary">Edit Your Tags</button></a>
            </div>
            <hr />
        </div>

        <!-- Container for cards -->
        <div class="container pt-4">
            <div class="row text-center d-flex">
                <!-- Column 1 -->
                <div class="col-md m-md-3">
                    <!-- Card -->
                    <a href="/post/viewPost.html" class="text-decoration-none">
                        <div class="card bg-secondary text-light mb-3 p-3 zoom">
                            <div class="card-body text-center">
                                <div class="d-flex justify-content-between">
                                    <!-- Tags -->
                                    <span class="text-start h6 text-body-emphasis opacity-75 rounded">
                                        <span class="badge bg-light text-dark m-1 rounded-pill">HTML</span>
                                        <span class="badge bg-light text-dark m-1 rounded-pill">CSS</span>
                                        <span class="badge bg-light text-dark m-1 rounded-pill">Really long tag...</span>
                                        <span class="badge bg-light text-dark m-1 rounded-pill">UBCO</span>
                                    </span>
                                    <!-- Time -->
                                    <span class="badge">3 mins ago</span>
                                </div>
                                <!-- post title  -->
                                <h3 class="card-title mt-lg-4 mb-4 text-light">You wouldn't believe what happened yesterday...</h3>
                                <!-- post image -->
                                <img src="/img/placeholder_img.webp" alt="Post Image" class="card-image img-fluid mb-3">
                                <!-- slider rating -->
                                <a>
                                    <div class="text-start mt-3">
                                        <!-- Read only slider, disabled just for aggregate rating, actually rate on view page -->
                                        <div class="d-flex justify-content-end">
                                            <input type="range" class="form-range" id="rating" value="2.75" min="0" max="5.5" step="0.01" disabled>
                                            <p class="mx-2" for="rating"><span id="aggregate-rate">2.75</span>/5.5</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- End slider rating -->
                            </div>
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <!-- End Column 1 -->

                <!-- Column 2 -->
                <div class="col-md m-md-3">
                
                </div>
                <!-- END Column 2 -->

            </div>
        </div>
    </section>
    <!-- END Briefing Section -->

    <!-- All posts section -->
    <section class="text-start">
        <div class="container p-3">
            <div class="d-flex align-items-center justify-content-between">
                <h1>5<span class="text-primary">.</span>5 </h1>
                <p class="lead my-2">All posts</p>
            </div>
            <hr />
        </div>

        <!-- Container for cards -->
        <div class="container pt-4">
            <div class="row text-center d-flex">
                <!-- Column 1 -->
                <div class="col-md m-md-3">
                    <!-- Card -->
                    <a href="/post/viewPost.html" class="text-decoration-none">
                        <div class="card bg-secondary text-light mb-3 p-3 zoom">
                            <div class="card-body text-center">
                                <div class="d-flex justify-content-between">
                                    <!-- Tags -->
                                    <span class="text-start h6 text-body-emphasis opacity-75 rounded">
                                        <span class="badge bg-light text-dark m-1 rounded-pill">HTML</span>
                                        <span class="badge bg-light text-dark m-1 rounded-pill">CSS</span>
                                        <span class="badge bg-light text-dark m-1 rounded-pill">Really long tag...</span>
                                        <span class="badge bg-light text-dark m-1 rounded-pill">UBCO</span>
                                    </span>
                                    <!-- Time -->
                                    <span class="badge">3 mins ago</span>
                                </div>
                                <!-- post title  -->
                                <h3 class="card-title mt-lg-4 mb-4 text-light">You wouldn't believe what happened yesterday...</h3>
                                <!-- post image -->
                                <img src="/img/placeholder_img.webp" alt="Post Image" class="card-image img-fluid mb-3">
                                <!-- slider rating -->
                                <a>
                                    <div class="text-start mt-3">
                                        <!-- Read only slider, disabled just for aggregate rating, actually rate on view page -->
                                        <div class="d-flex justify-content-end">
                                            <input type="range" class="form-range" id="rating" value="2.75" min="0" max="5.5" step="0.01" disabled>
                                            <p class="mx-2" for="rating"><span id="aggregate-rate">2.75</span>/5.5</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- End slider rating -->
                            </div>
                        </div>
                    </a>
                    <!-- End Card -->

                </div>
                <!-- End Column 1 -->
                <!-- Column 2 -->
                <div class="col-md m-md-3">
                </div>
                <!-- END Column 2 -->
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container">
                        <button type="button" class="btn-close float-start me-5" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title">Login or Signup:</h4>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <script src="https://accounts.google.com/gsi/client" async></script>
                        <div id="g_id_onload" data-client_id="YOUR_GOOGLE_CLIENT_ID" data-login_uri="https://your.domain/your_login_endpoint" data-auto_prompt="false">
                        </div>
                        <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline" data-text="sign_in_with" data-shape="rectangular" data-logo_alignment="left" style="width: 200px; margin: 2em 0 2em 8em; ">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Popup (will be moved into separate file) -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container">
                        <button type="button" class="btn-close  float-start me-5" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title">Login or Signup:</h4>
                    </div>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="InputName">Username</label>
                            <input type="text" class="form-control" id="InputName" aria-describedby="nameHelp" placeholder="Enter username">
                        </div>
                        <div class="form-group mb-3">
                            <label for="InputEmail">Email address</label>
                            <input type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                        <div class="form-group mb-3">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="mb-3">
                            <label for="profilePicture" class="form-label">(Optional) Select a Profile Image:</label>
                            <input type="file" class="form-control" id="profilePicture" accept="image/*" onchange="displayImage(this.files)" required>
                            <img id="previewImage" class="img-fluid mt-2" style="display: none;" alt="Select profile Pic">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mx-auto">Login/Register</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="container mx-auto">
                        <script src="https://accounts.google.com/gsi/client" async></script>
                        <div id="g_id_onload mx-auto" data-client_id="YOUR_GOOGLE_CLIENT_ID" data-login_uri="https://your.domain/your_login_endpoint" data-auto_prompt="false">
                        </div>
                        <div class="g_id_signin mx-auto" data-type="standard" data-size="large" data-theme="outline" data-text="sign_in_with" data-shape="rectangular" data-logo_alignment="left" style="width: 200px; margin: 1em 0 1em 0; ">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Load nav -->
    <script>
        $(function() {
            $("#nav").load("/nav.html");
        });
        $('#myModal').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus')
        })
    </script>
</body>


</html>