<?php 
    require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_HEADER.php";

    if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
        header("location: $BASE_URL/auth/");
        exit;
    }
    
    $old_password = $old_password_err = "";
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Validate old password
        if(empty(trim($_POST['old_password']))) {
            $old_password_err = "Plese enter your old password.";
        } else {
            $sql = "SELECT password, salt FROM users WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                $param_id = $_SESSION['id'];
                mysqli_stmt_bind_param($stmt, "s", $param_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $hashed_password, $salt);
                while (mysqli_stmt_fetch($stmt)) {
                    $hashed_password = $hashed_password;
                    $salt = $salt;
                }
            }
            
            if (password_verify(trim($_POST['old_password']) . $salt, $hashed_password)) {
                // Validate new password
                $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d\s]).{8,}$/';

                if(empty(trim($_POST["new_password"]))){
                    $new_password_err = "Please enter the new password.";     
                } elseif (!preg_match($pattern, $_POST["new_password"])) {
                    $new_password_err = "Password must have:
                        <ul>
                            <li>At least one lowercase letter</li>
                            <li>At least one uppercase letter</li>
                            <li>At least one digit</li>
                            <li>At least one symbol</li>
                            <li>Minimum length of 8 characters</li>
                        </ul>";
                } else{
                    $new_password = trim($_POST["new_password"]);
                }
                
                // Validate confirm password
                if(empty(trim($_POST["confirm_password"]))){
                    $confirm_password_err = "Please confirm the password.";
                } else{
                    $confirm_password = trim($_POST["confirm_password"]);
                    if(empty($new_password_err) && ($new_password != $confirm_password)){
                        $confirm_password_err = "Password did not match.";
                    }
                }
                $salt = hash('sha256', date('Y-m-d H:i:s'));
                // Check input errors before updating the database
                if(empty($new_password_err) && empty($confirm_password_err) && empty($old_password_err)){
                    // Prepare an update statement
                    $sql = "UPDATE users SET password = ?, salt = ? WHERE id = ?";
                    
                    if($stmt = mysqli_prepare($link, $sql)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ssi", $param_password, $param_salt, $param_id);
                        
                        // Set parameters
                        $param_password = password_hash($new_password . $salt, PASSWORD_DEFAULT);
                        $param_id = $_SESSION["id"];
                        $param_salt = $salt;
                        
                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt)){
                            // Password updated successfully. Destroy the session, and redirect to login page
                            session_destroy();
                            header("location: $BASE_URL/auth/login");
                            exit();
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt);
                    }
                }
            } else {
                $old_password_err = "Incorrect Password.";
            }
        }
        
        // Close connection
        mysqli_close($link);
    }
    ?>
    <div class="card">
        <div class="card-header">
            <h2>Reset Password</h2>
        </div>
        <div class="card-body">
            <p>Please fill out this form to reset your password.</p>
            <?php 
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                    <label>Old Password</label>
                    <input type="password" name="old_password" class="form-control <?php echo (!empty($old_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $old_password; ?>">
                    <span class="invalid-feedback"><?php echo $old_password_err; ?></span>
                </div>
                <br>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                    <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                </div>
                <br>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <br>
                <div class="form-group">
                    <input type="submit" class="btn btn-outline-primary" value="Submit">
                    <a class="btn btn-link ml-2" href="<?php echo $BASE_URL; ?>">Cancel</a>
                </div>
            </form>
        </div>
    </div>    
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_FOOTER.php";
    ?>