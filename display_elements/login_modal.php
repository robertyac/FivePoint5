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
