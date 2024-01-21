<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_HEADER.php";

// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: $BASE_URL/auth/");
    exit;
}
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    $salt = hash('sha256', date('Y-m-d H:i:s'));

    // Validate password
    // For this pattern, the minimum number of possible combinations depends on the allowed characters.
    // Assuming the pattern allows all printable ASCII characters (total of 94 characters), the minimum
    // number of possible combinations can be calculated as follows:
    // Total possible combinations = 94^8
    // Thus, there would be approximately 6,095,689,385,410,816 (6 quadrillion) possible combinations.
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d\s]).{8,}$/';
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (preg_match($pattern, $_POST["password"])) {
        $password = trim($_POST["password"]);
        $salted_password = $password . $salt;
    } else {
        $password_err = "Password must have:
            <ul>
                <li>At least one lowercase letter</li>
                <li>At least one uppercase letter</li>
                <li>At least one digit</li>
                <li>At least one symbol</li>
                <li>Minimum length of 8 characters</li>
            </ul>";
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Passwords do not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, salt) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_salt);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($salted_password, PASSWORD_DEFAULT);
            $param_salt = $salt;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: $BASE_URL/auth/login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
    <div class="card <?php if ($_SESSION['mode'] == "dark") { echo "bg-dark text-light"; } ?>">
        <div class="card-header">
            <h2>Create New User</h2>
        </div>
        <div class="card-body">
            <p>Please fill this form to create an account.</p>
            <?php 
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <br>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <br>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <br>
                <div class="form-group">
                    <center>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <input type="reset" class="btn btn-secondary ml-2" value="Reset">
                        <a class="btn-link ml-2" href="<?php echo $BASE_URL; ?>">Cancel</a>
                    </center>
                </div>
            </form>
        </div>
    </div>
<?php
    
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_FOOTER.php";
    ?>