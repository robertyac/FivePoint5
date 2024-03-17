<!-- NavBar -->
<nav class="navbar bg-dark-subtle fixed-top p-0" style="height: 4em;">
    <div class="container-fluid d-flex flex-row justify-content-center flex-nowrap navbar-expand-lg">

        <!-- LOGO -->
        <!-- First Div (5.5) - Visible on smaller screens -->
        <div class="d-flex align-items-center d-block d-lg-none mx-1">
            <a class="navbar-brand" href="/index.php">
                <img src="/cosc360_proj/display_elements/5.5.png" alt="Logo" width="45" class="d-inline-block align-text-top">
            </a>
        </div>
        <!-- Second Div (word logo)- Visible on lg screens and up -->
        <div class="d-none d-lg-block">
            <a class="navbar-brand" href="/index.php">
               <img src="/cosc360_proj/display_elements/FivePoint5.png" alt="Logo" width="225" class="d-inline-block align-text-top">
            </a>
        </div>

        <!-- Tags Dropdown Menu -->
        <div class="dropdown m-auto">
            <button class="btn-outline-success btn dropdown-toggle mx-1 mx-sm-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Tags
            </button>
            <!-- Search for a tag dropdown-->
            <ul class="dropdown-menu end-0 mt-2" style="min-width: 250px;">
                <form class="navbar-form d-flex mx-2" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Tag">
                    </div>
                    <button class="btn btn-outline-success ml-2" type="submit">Find
                    </button>
                </form>
                <!-- Recent Tags -->
                <li><a class="dropdown-item" href="#">Recent Tag</a></li>
                <li><a class="dropdown-item" href="#">Recent Tag</a></li>
                <li><a class="dropdown-item" href="#">Recent Tag</a></li>
            </ul>
        </div>

        <!-- Search -->
        <!-- Search button (smaller displays)-->
        <div class="d-flex d-sm-none m-auto">
            <div class="dropdown d-grid">
                <button class="btn btn-outline-dark mx-1 px-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <!--Search Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </button>
                <!-- Search dropdown-->
                <div class="dropdown-menu mt-3" style="width: 250px; left:50%; margin-left:-125px;">
                    <form class="navbar-form d-flex mx-2" action="">
                        <div class="form-group w-100">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Form (larger Displays) -->
        <div class="flex-grow-1 mx-2 d-none d-sm-block">
            <form class="d-flex align-items-center" role="search">
                <input class="form-control me-2" type="search" placeholder="Search FivePoint5" aria-label="Search">
            </form>
        </div>


        <!-- Profile Button -->
        <!-- have to use order-last but code before create post because of some bug with live server -->
        <div class="dropdown d-flex justify-content-center mx-1 order-last">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://xsgames.co/randomusers/assets/images/favicon.png" class="rounded-circle" height="45" alt="Profile Picture" />
            </a>
            <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="navbarDropdownMenuLink">
                <li><a class="dropdown-item" href="/cosc360_proj/profile/profile.html">My profile</a></li>
                <li><a class="dropdown-item btn btn-primary" href="#loginModal" role="button" data-bs-toggle="modal" data-bs-target="#loginModal">Login/Logout</a></li>
            </ul>
        </div>

        <!-- Create Post Button -->
        <div class="d-flex m-auto">
            <a href="/cosc360_proj/post/createPost.php">
                <button class="btn btn-outline-dark mx-1 mx-sm-2 px-4" type="button">
                    <!--Plus Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                    </svg>
                </button>
            </a>
        </div>



    </div>
</nav>