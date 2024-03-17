<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FivePoint5 - Home</title>
    <link rel="icon" type="image/x-icon" href="/img/5.5.ico">
    <link rel="apple-touch-icon" href="/img/5.5-white.png">
    <!-- Index stylesheet -->
    <link rel="stylesheet" href="css/index.css">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-body-secondary">
    <!--Navigation bar-->
    <div id="nav" style="height: 100px;"><?php include 'display_elements/nav.php'; ?></div>
    <!-- Login modal -->
    <?php include 'display_elements/login_modal.php'; ?>
    <!--End of Navigation bar-->
    <!--Briefing Section -->
    <section class="text-center text-lg-start">
        <div class="container p-2">
            <div class="row align-items-center d-flex justify-content-between">
                <div class="col-sm-12 col-lg-10">
                    <h1>Your 5<span class="text-primary">.</span>5 Briefing</h1>
                    <p class="lead my-2">Catch up on recent posts with your favourite tags.</p>
                </div>
                <div class="col-sm-12 col-lg-2 text-right">
                    <a href=""><button class="zoom btn btn-primary">Edit Your Tags</button></a>
                </div>
            </div>
            <hr />
        </div>

        <!-- Container for cards -->
        <div class="container pt-4">
            <div class="row text-center d-flex">
                <!-- Column 1 -->
                <div class="col-md m-md-3">
                    <p>We will put a users top 6 most relevent posts here</p>
                </div>
                <!-- End Column 1 -->

                <!-- Column 2 -->
                <div class="col-md m-md-3">
                    <p>We will put a users top 6 most relevent posts here</p>
                </div>
                <!-- END Column 2 -->

            </div>
        </div>
    </section>
    <!-- END Briefing Section -->

    <!-- All posts section -->
    <section class="text-start">
        <div class="container px-4">
            <div class="d-flex align-items-center justify-content-between">
                <h1>5<span class="text-primary">.</span>5 </h1>
                <p class="lead my-0">All posts</p>
            </div>
            <hr />
        </div>

        <!-- Container for cards -->
        <div class="container pt-4">
            <div class="row text-center d-flex">
                <!-- Column 1 -->
                <div class="col-md m-md-3">
                    <?php
                    $posts = include "commands/getPost.php";
                    // reverse the array so the most recent posts are first
                    $posts = array_reverse($posts);
                    $counter = 0;
                    foreach ($posts as $post) {
                        if ($counter % 2 == 0) {
                            include "templates/postCard.php";
                        }
                        $counter++;
                    }
                    ?>
                </div>
                <!-- End Column 1 -->
                <!-- Column 2 -->
                <div class="col-md m-md-3">
                    <?php
                    $counter = 0;
                    foreach ($posts as $post) {
                        if ($counter % 2 != 0) {
                            include "templates/postCard.php";
                        }
                        $counter++;
                    }
                    ?>
                </div>
                <!-- END Column 2 -->
            </div>
        </div>
    </section>
 
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Tags Ajax -->
    <script src="commands/ajaxTags.js"></script>
</body>
</html>