<!-- Login Popup-->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container">
                    <button type="button" class="btn-close float-start me-5 mt-1" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    <h4 class="modal-title">Login</h4>
                </div>
            </div>
            <div class="modal-body">
                <form action="commands/login.php" method="post">
                    <div class="form-group mb-3">
                        <label for="usernameInputLogin">Username</label>
                        <input type="text" class="form-control" id="usernameInputLogin" aria-describedby="nameHelp"
                               placeholder="Enter username" name="usernameInputLogin">
                    </div>
                    <div class="form-group mb-3">
                        <label for="passwordInputLogin">Password</label>
                        <input type="password" class="form-control" id="passwordInputLogin" placeholder="Password"
                               name="passwordInputLogin"> <!-- this may send the raw password over the net without https -->
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mx-auto">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Register Popup-->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container">
                    <button type="button" class="btn-close float-start me-5 mt-1" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    <h4 class="modal-title ml-5">Signup:</h4>
                </div>
            </div>
            <div class="modal-body">
                <form action="commands/register.php" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="usernameInput">Username</label>
                        <input type="text" class="form-control" id="usernameInput" name="usernameInput"
                               aria-describedby="nameHelp"
                               placeholder="Enter username">
                    </div>
                    <div class="form-group mb-3">
                        <label for="emailInput">Email address</label>
                        <input type="email" class="form-control" id="emailInput" name="emailInput"
                               aria-describedby="emailHelp"
                               placeholder="Enter email">
                    </div>
                    <div class="form-group mb-3">
                        <label for="passwordInput">Password</label>
                        <input type="password" class="form-control" id="passwordInput" name="passwordInput"
                               placeholder="Password" minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="profilePictureInput" class="form-label">(Optional) Select a Profile Image:</label>
                        <input type="file" class="form-control" id="profilePictureInput" accept="image/*"
                               onchange="displayImage(this.files)" name="profilePicInput">
                        <img id="previewImage" class="img-fluid mt-2" style="display: none;" alt="Select profile Pic">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mx-auto">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>