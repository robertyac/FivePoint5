<?php
include 'commands/getProfilePic.php';
session_start();
$loggedIn = false;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $loggedIn = true;
}
$tag = isset($_GET['tag']) ? $_GET['tag'] : '';
if ($tag) {
    $_SESSION['recent_tags'] = $_SESSION['recent_tags'] ?? [];
    if (($key = array_search($tag, $_SESSION['recent_tags'])) !== false) {
        unset($_SESSION['recent_tags'][$key]);
    }
    array_unshift($_SESSION['recent_tags'], $tag);
    $_SESSION['recent_tags'] = array_slice($_SESSION['recent_tags'], 0, 5); // Keep only the 5 most recent tags
}
$recent_tags = $_SESSION['recent_tags'] ?? [];
?>

<!-- NavBar -->
<nav class="navbar bg-dark-subtle fixed-top p-0" style="height: 4em;">
    <div class="container-fluid d-flex flex-row justify-content-center flex-nowrap navbar-expand-lg">

        <!-- LOGO -->
        <!-- First Div (5.5) - Visible on smaller screens -->
        <div class="d-flex align-items-center d-block d-lg-none mx-1">
            <a class="navbar-brand" href="/FivePoint5/index.php">
                <img src="display_elements/5.5.png" alt="Logo" width="45"
                     class="d-inline-block align-text-top">
            </a>
        </div>
        <!-- Second Div (word logo)- Visible on lg screens and up -->
        <div class="d-none d-lg-block">
            <a class="navbar-brand" href="/FivePoint5/index.php">
                <img src="/FivePoint5/display_elements/FivePoint5.png" alt="Logo" width="225"
                     class="d-inline-block align-text-top">
            </a>
        </div>

        <!-- Tags Dropdown Menu -->
        <div class="dropdown m-auto">
            <button class="btn-outline-success btn dropdown-toggle mx-1 mx-sm-2 px-lg-4" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false"> Tag
            </button>
            <!-- Search for a tag dropdown-->
            <ul class="dropdown-menu end-0 mt-2" style="min-width: 270px;">
            <form class="navbar-form d-flex mx-2" action="index.php#allPosts" method="get">
                    <div class="form-group">
                        <input type="text" class="form-control" id="tagInput" name="tag" placeholder="Tag">
                    </div>
                    <button class="btn btn-outline-success" type="submit" style="white-space: nowrap;">Search By Tag</button>
                </form>
                <!-- Recent Tags -->
                <?php
                foreach ($recent_tags as $recent_tag) {
                    echo '<li><a class="dropdown-item" href="index.php?tag=' . urlencode($recent_tag) . '">' . htmlspecialchars($recent_tag) . '</a></li>';
                }
                ?>
            </ul>
        </div>
        <!-- Search -->
        <!-- Search button (smaller displays)-->
        <div class="d-flex d-sm-none m-auto">
            <div class="dropdown d-grid">
                <button class="btn btn-outline-dark mx-1 px-4" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <!--Search Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
                <!-- Search dropdown-->
                <div class="dropdown-menu mt-3" style="width: 250px; left:50%; margin-left:-125px;">
                    <form class="navbar-form d-flex mx-2" action="index.php#allPosts" method="get">
                        <div class="form-group w-100">
                            <input type="text" name="search" class="form-control" placeholder="Search">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Form (larger Displays) -->
        <div class="flex-grow-1 mx-2 d-none d-sm-block">
            <form class="d-flex align-items-center" action="index.php#allPosts" method="get" role="search">
                <input class="form-control me-2" type="search" name="search" placeholder="Search FivePoint5"
                       aria-label="Search">
            </form>
        </div>

        <!-- Profile Button -->
        <!-- have to use order-last but code before create post because of some bug with live server -->
        <div class="dropdown d-flex justify-content-center mx-1 order-last">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
                <?php
                if ($loggedIn) {
                    $image_blob = getProfilePic($user);
                    $encoded = base64_encode($image_blob);
                    echo '<img src="data:image/png;base64,' . $encoded . '" height="45" alt="Profile Picture"/>';
//                    echo '<img src="display_elements/5.5.png" height="45" alt="Profile Picture"/>';
                } else {
                    echo '<img src="https://xsgames.co/randomusers/assets/images/favicon.png" height="45" alt="Not logged in pic"/>';
                }
                ?>

            </a>
            <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="navbarDropdownMenuLink">
                <?php
                echo "<script>console.log(" . json_encode($loggedIn) . ")</script>";
                if ($loggedIn) {
                    echo "<li><a class='dropdown-item' href='profile.php'>My profile</a></li>";
                    echo "<li><a class='dropdown-item btn btn-primary' href='commands/logout.php'>Logout</a></li>";
                } else {
                    echo "<li><a class='dropdown-item btn btn-primary' href='#loginModal' role='button' data-bs-toggle='modal' data-bs-target='#loginModal'>Login</a></li>";
                    echo "<li><a class='dropdown-item btn btn-primary' href='#registerModal' role='button' data-bs-toggle='modal' data-bs-target='#registerModal'>Register</a></li>";
                }
                ?>
            </ul>
        </div>

        <!-- Create Post Button -->
        <div class="d-flex m-auto">
            <a href="/FivePoint5/post/createPost.php">
                <button class="btn btn-outline-dark mx-1 mx-sm-2 px-4" type="button">
                    <!--Plus Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                    </svg>
                </button>
            </a>
        </div>


    </div>
</nav>
