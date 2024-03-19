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
                <form action="commands/login.php" method="post" novalidate>
                    <div class="form-group mb-3">
                        <label for="usernameInputLogin">Username</label>
                        <input type="text" class="form-control" id="usernameInputLogin" aria-describedby="nameHelp"
                               placeholder="Enter username" name="usernameInputLogin" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="passwordInputLogin">Password</label>
                        <input type="password" class="form-control" id="passwordInputLogin" placeholder="Password"
                               name="passwordInputLogin" required>
                        <!-- this may send the raw password over the net without https -->
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
                <form action="commands/register.php" method="post" enctype="multipart/form-data" novalidate>
                    <div class="form-group mb-3">
                        <label for="usernameInput">Username</label>
                        <input type="text" class="form-control" id="usernameInput" name="usernameInput"
                               aria-describedby="nameHelp" placeholder="Enter username" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="emailInput">Email address</label>
                        <input type="email" class="form-control" id="emailInput" name="emailInput"
                               aria-describedby="emailHelp" placeholder="Enter email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="passwordInput">Password</label>
                        <input type="password" class="form-control" id="passwordInput" name="passwordInput"
                               placeholder="Password" minlength="6"  required>
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

<!-- JavaScript form Validation -->
<script>
function removeInvalid(input, tooltip) {
    input.classList.remove("is-invalid");
    tooltip.dispose();
}

document.getElementById("loginModal").addEventListener("submit", function(event){
    var username = document.getElementById("usernameInputLogin");
    var password = document.getElementById("passwordInputLogin");
    var usernameTooltip, passwordTooltip;

    if(username.value === "" || password.value === ""){
        event.preventDefault();
        if(username.value === "") {
            username.classList.add("is-invalid");
            usernameTooltip = new bootstrap.Tooltip(username, {
                title: "Username cannot be empty",
                placement: "right"
            });
        }
        if(password.value === "") {
            password.classList.add("is-invalid");
            passwordTooltip = new bootstrap.Tooltip(password, {
                title: "Password cannot be empty",
                placement: "right"
            });
        }
    }

    username.addEventListener("input", function() {
        if(username.value !== "" && usernameTooltip) {
            removeInvalid(username, usernameTooltip);
        }
    });

    password.addEventListener("input", function() {
        if(password.value !== "" && passwordTooltip) {
            removeInvalid(password, passwordTooltip);
        }
    });
});

document.getElementById("registerModal").addEventListener("submit", function(event){
    var username = document.getElementById("usernameInput");
    var email = document.getElementById("emailInput");
    var password = document.getElementById("passwordInput");
    var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
    var usernameTooltip, emailTooltip, passwordTooltip;

    if(username.value === "" || email.value === "" || password.value === "" || !email.value.match(emailPattern) || !password.value.match(passwordPattern)){
        event.preventDefault();
        if(username.value === "") {
            username.classList.add("is-invalid");
            usernameTooltip = new bootstrap.Tooltip(username, {
                title: "Username cannot be empty",
                placement: "right"
            });
        }
        if(email.value === "") {
            email.classList.add("is-invalid");
            emailTooltip = new bootstrap.Tooltip(email, {
                title: "Email cannot be empty",
                placement: "right"
            });
        } else if(!email.value.match(emailPattern)){
            email.classList.add("is-invalid");
            emailTooltip = new bootstrap.Tooltip(email, {
                title: "Please enter a valid email address",
                placement: "right"
            });
        }
        if(password.value === "") {
            password.classList.add("is-invalid");
            passwordTooltip = new bootstrap.Tooltip(password, {
                title: "Password cannot be empty",
                placement: "right"
            });
        } else if(!password.value.match(passwordPattern)){
            password.classList.add("is-invalid");
            passwordTooltip = new bootstrap.Tooltip(password, {
                title: "Password must be at least 6 characters long, contain at least one digit, one lowercase and one uppercase letter",
                placement: "right"
            });
        }
    }

    username.addEventListener("input", function() {
        if(username.value !== "" && usernameTooltip) {
            removeInvalid(username, usernameTooltip);
        }
    });

    email.addEventListener("input", function() {
        if(email.value !== "" && email.value.match(emailPattern) && emailTooltip) {
            removeInvalid(email, emailTooltip);
        }
    });

    password.addEventListener("input", function() {
        if(password.value !== "" && password.value.match(passwordPattern) && passwordTooltip) {
            removeInvalid(password, passwordTooltip);
        }
    });
});
</script>