<?php
$config = require 'commands/config.php';
$siteKey = $config['recaptcha']['site_key'];
?>
<!-- Login Popup-->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container">
                    <button type="button" class="btn-close float-start me-5 mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title">Login</h4>
                </div>
            </div>
            <div class="modal-body">
                <form id="loginForm" action="commands/login.php" method="post" novalidate>
                    <div class="form-group mb-3">
                        <label for="usernameInputLogin">Username</label>
                        <input type="text" class="form-control" id="usernameInputLogin" aria-describedby="nameHelp" placeholder="Enter username" name="usernameInputLogin" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="passwordInputLogin">Password</label>
                        <input type="password" class="form-control" id="passwordInputLogin" placeholder="Password" name="passwordInputLogin" required>
                        <!-- this may send the raw password over the net without https -->
                    </div>
                    <div id="recaptcha-parent" class="d-flex mb-3 justify-content-center">
                        <div id="recaptcha-box-login">
                            <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
                        </div>
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
                    <button type="button" class="btn-close float-start me-5 mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title ml-5">Signup:</h4>
                </div>
            </div>
            <div class="modal-body">
                <form id="registerForm" action="commands/register.php" method="post" enctype="multipart/form-data" novalidate>
                    <div class="form-group mb-3">
                        <label for="usernameInput">Username</label>
                        <input type="text" class="form-control" id="usernameInput" name="usernameInput" aria-describedby="nameHelp" placeholder="Enter username" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="emailInput">Email address</label>
                        <input type="email" class="form-control" id="emailInput" name="emailInput" aria-describedby="emailHelp" placeholder="Enter email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="passwordInput">Password</label>
                        <input type="password" class="form-control" id="passwordInput" name="passwordInput" placeholder="Password" minlength="6" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="passwordConfirmInput">Confirm Password</label>
                        <input type="password" class="form-control" id="passwordConfirmInput" name="passwordConfirmInput" placeholder="Confirm Password" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label for="profilePictureInput" class="form-label">(Optional) Select a Profile Image:</label>
                        <input type="file" class="form-control" id="profilePictureInput" accept="image/*" onchange="displayImage(this.files)" name="profilePicInput">
                        <img id="previewImage" class="img-fluid mt-2" style="display: none;" alt="Select profile Pic">
                    </div>
                    <div id="recaptcha-parent" class="d-flex mb-3 justify-content-center">
                        <div id="recaptcha-box-register">
                            <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mx-auto">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript form Validation -->
<script>
    function removeInvalid(input, tooltip) {
        input.classList.remove("is-invalid");
        tooltip.dispose();
    }

    // login form validation
    document.getElementById("loginModal").addEventListener("submit", function(event) {
        var username = document.getElementById("usernameInputLogin");
        var password = document.getElementById("passwordInputLogin");
        var usernameTooltip, passwordTooltip;

        if (username.value === "" || password.value === "") {
            event.preventDefault();
            if (username.value === "") {
                username.classList.add("is-invalid");
                usernameTooltip = new bootstrap.Tooltip(username, {
                    title: "Username cannot be empty",
                    placement: "right"
                });
            }
            if (password.value === "") {
                password.classList.add("is-invalid");
                passwordTooltip = new bootstrap.Tooltip(password, {
                    title: "Password cannot be empty",
                    placement: "right"
                });
            }
        }

        username.addEventListener("input", function() {
            if (username.value !== "" && usernameTooltip) {
                removeInvalid(username, usernameTooltip);
            }
        });

        password.addEventListener("input", function() {
            if (password.value !== "" && passwordTooltip) {
                removeInvalid(password, passwordTooltip);
            }
        });
    });

    // register form validation
    document.getElementById("registerModal").addEventListener("submit", function(event) {
        var username = document.getElementById("usernameInput");
        var email = document.getElementById("emailInput");
        var password = document.getElementById("passwordInput");
        var passwordConfirm = document.getElementById("passwordConfirmInput");
        var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
        var usernameTooltip, emailTooltip, passwordTooltip, passwordConfirmTooltip;

        if (username.value === "" || email.value === "" || password.value === "" || password.value !== passwordConfirm.value || !email.value.match(emailPattern) || !password.value.match(passwordPattern)) {
            event.preventDefault();
            if (password.value !== passwordConfirm.value) {
                password.classList.add("is-invalid");
                passwordConfirm.classList.add("is-invalid");
                passwordTooltip = new bootstrap.Tooltip(password, {
                    title: "Passwords do not match",
                    placement: "right"
                });
                passwordConfirmTooltip = new bootstrap.Tooltip(passwordConfirm, {
                    title: "Passwords do not match",
                    placement: "right"
                });
            }
            if (username.value === "") {
                username.classList.add("is-invalid");
                usernameTooltip = new bootstrap.Tooltip(username, {
                    title: "Username cannot be empty",
                    placement: "right"
                });
            }
            if (email.value === "") {
                email.classList.add("is-invalid");
                emailTooltip = new bootstrap.Tooltip(email, {
                    title: "Email cannot be empty",
                    placement: "right"
                });
            } else if (!email.value.match(emailPattern)) {
                email.classList.add("is-invalid");
                emailTooltip = new bootstrap.Tooltip(email, {
                    title: "Please enter a valid email address",
                    placement: "right"
                });
            }
            if (password.value === "") {
                password.classList.add("is-invalid");
                passwordTooltip = new bootstrap.Tooltip(password, {
                    title: "Password cannot be empty",
                    placement: "right"
                });
            } else if (!password.value.match(passwordPattern)) {
                password.classList.add("is-invalid");
                passwordTooltip = new bootstrap.Tooltip(password, {
                    title: "Password must be at least 6 characters long, contain at least one digit, one lowercase and one uppercase letter",
                    placement: "right"
                });
            }
        }

        username.addEventListener("input", function() {
            if (username.value !== "" && usernameTooltip) {
                removeInvalid(username, usernameTooltip);
            }
        });

        email.addEventListener("input", function() {
            if (email.value !== "" && email.value.match(emailPattern) && emailTooltip) {
                removeInvalid(email, emailTooltip);
            }
        });

        password.addEventListener("input", function() {
            if (password.value !== "" && password.value.match(passwordPattern)) {
                if (passwordTooltip) {
                    passwordTooltip.hide();
                    password.classList.remove("is-invalid");
                }
            }
        });

        passwordConfirm.addEventListener("input", function() {
            if (password.value === passwordConfirm.value) {
                if (passwordTooltip) {
                    passwordTooltip.hide();
                    password.classList.remove("is-invalid");
                }
                if (passwordConfirmTooltip) {
                    passwordConfirmTooltip.hide();
                    passwordConfirm.classList.remove("is-invalid");
                }
            }
        });
    });
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>