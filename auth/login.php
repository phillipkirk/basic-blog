<?php
    // Include config file
    require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_HEADER.php";

    // Load CAPTCHA files if enabled
    if ($env['CAPTCHA'] == 'on') {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/CAPTCHA/funct/generateCaptcha.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/CAPTCHA/funct/generateRandomString.php";
    }

    // Check if the user is already logged in, if yes then redirect him to welcome page
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: $BASE_URL/blog/");
        exit;
    }


    // Define variables and initialize with empty values
    $username = $password = "";
    $username_err = $password_err = $login_err = $captcha_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($env['CAPTCHA'] === 'on') {
            $CODE_H = $_POST['code'];
            $USER_CODE = $_POST['code_send'];
            $TEST_CODE = hash('adler32', $USER_CODE);
        } else {
            $CODE_H = $USER_CODE = $TEST_CODE = '';
        }
        if ($TEST_CODE === $CODE_H) {
            // Processing form data when form is submitted
            // Check if username is empty
            if(empty(trim($_POST["username"]))){
                $username_err = $loc['username_err'];
            } else{
                $username = trim($_POST["username"]);
            }
            
            // Check if password is empty
            if(empty(trim($_POST["password"]))){
                $password_err = $loc['password_err'];
            } else{
                $password = trim($_POST["password"]);
            }

            define('MAX_LOGIN_ATTEMPTS', $env['MAX_LOGIN_ATTEMPTS']);
            define('LOGIN_ATTEMPT_PERIOD', $env['LOGIN_ATTEMPT_PERIOD']);
            
            // Validate credentials
            if (empty($username_err) && empty($password_err)) {
                // Prepare a select statement
                $sql = "SELECT id, username, password, salt FROM users WHERE username = ?";
            
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $param_username = $username;
            
                    mysqli_stmt_bind_param($stmt, "s", $param_username);
            
                    // Check if the user has reached the maximum number of login attempts within the defined period
                    $sql_attempts = "SELECT COUNT(*) FROM login_attempts WHERE username = ? AND timestamp > DATE_SUB(NOW(), INTERVAL ? SECOND)";
                    if ($stmt_attempts = mysqli_prepare($link, $sql_attempts)) {
                        $param_username = $username;
                        $param_attempt_period = LOGIN_ATTEMPT_PERIOD;
            
                        mysqli_stmt_bind_param($stmt_attempts, "si", $param_username, $param_attempt_period);
                        mysqli_stmt_execute($stmt_attempts);
            
                        $attempt_count = 0; // Initialize attempt_count
                        mysqli_stmt_bind_result($stmt_attempts, $attempt_count);
                        mysqli_stmt_fetch($stmt_attempts);
                        mysqli_stmt_close($stmt_attempts);
            
                        if ($attempt_count >= MAX_LOGIN_ATTEMPTS) {
                            $login_err = $loc['login_err_a'];
                        } else {
                            // Attempt to execute the prepared statement
                            if (mysqli_stmt_execute($stmt)) {
                                // Store result
                                mysqli_stmt_store_result($stmt);
            
                                // Check if username exists, if yes then verify password
                                if (mysqli_stmt_num_rows($stmt) == 1) {
                                    // Bind result variables
                                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $salt);
                                    if (mysqli_stmt_fetch($stmt)) {              
                                        $password = $password . $salt;
                                        if (password_verify($password, $hashed_password)) {
                                            
                                            // Password is correct, so start a new session
                                            session_start();
                                            session_regenerate_id();
            
                                            // Store data in session variables
                                            $_SESSION["loggedin"] = true;
                                            $_SESSION["id"] = $id;
                                            $_SESSION["username"] = $username;
                                            // Redirect user to welcome page
                                            $sql = "INSERT INTO login_log (username, status) VALUES ('$username', 'PASS')";
                                            $link->query($sql);
                                            // Clean up log
                                            define('MAX_LOGIN_ATTEMPTS', 5);
                                            define('LOGIN_ATTEMPT_PERIOD', 300); // 5 minutes (300 seconds)

                                            // Clean up expired login attempts
                                            $sql = "DELETE FROM login_attempts WHERE timestamp < DATE_SUB(NOW(), INTERVAL ? SECOND)";
                                            if ($stmt = mysqli_prepare($link, $sql)) {
                                                $param_attempt_period = LOGIN_ATTEMPT_PERIOD;
                                                mysqli_stmt_bind_param($stmt, "s", $param_attempt_period); // Fixed: Corrected the placeholder and binding variable
                                                mysqli_stmt_execute($stmt);
                                                mysqli_stmt_close($stmt);
                                            }
                                            header("location: $BASE_URL/blog/");
                                        } else {
                                            // Insert a record into the login_attempts table
                                            $sql = "INSERT INTO login_attempts (username) VALUES (?)";
                                            if ($stmt = mysqli_prepare($link, $sql)) {
                                                mysqli_stmt_bind_param($stmt, "s", $username);
                                                mysqli_stmt_execute($stmt);
                                                mysqli_stmt_close($stmt);
                                            }
                                            // Password is not valid, display a generic error message
                                            $sql = "INSERT INTO login_log (username, status) VALUES ('$username', 'FAIL (Password)')";
                                            $link->query($sql);
                                            $login_err = $loc["login_err_b"];
                                        }
                                    }
                                } else {
                                    // Username doesn't exist, display a generic error message
                                    $sql = "INSERT INTO login_log (username, status) VALUES ('$username', 'FAIL (Invalid Username)')";
                                    $login_err = $loc["login_err_b"];
                                }
                            } else {
                                
                                $sql = "INSERT INTO login_log (username, status) VALUES ('$username', 'FAIL (Unknown)')";
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                        }
                    }
                }
            }
            
        } else {
            $captcha_err = $loc['captcha_err'];
        }
    }
    ?>
    <div class='content'>
        <div class="card <?php if ($_SESSION['mode'] == "dark") { echo "bg-dark text-light"; } ?>">
            <div class="card-header">
                <h2><?php echo $loc['login']; ?></h2>
            </div>
            <div class="card-body">
                <p><?php echo $loc['login_message']; ?></p>
                <?php 
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    ?>
                <span class="invalid-feedback"><?php echo $login_err; ?></span>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="form-group">
                        <label><?php echo $loc['username_2']; ?></label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>
                    <br>  
                    <div class="form-group">
                        <label><?php echo $loc['password']; ?></label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <?php
                            if ($env['CAPTCHA'] === 'on') {
                                $SECRET_A = generateRandomString(32);
                                $SECRET_B = generateRandomString(32);
                                $DATE = date("D M j G:i:s T Y");
                                $CODE = $SECRET_A . $DATE . $SECRET_B;
                                $CODE_L = hash('SHA512', $CODE);
                                $CODE_S = hash('adler32', $CODE_L);
                                $CODE_H = hash('adler32', $CODE_S);
                                echo "<center><br>";
                                echo generateCaptcha($CODE_S);
                                echo "<br></center>";
                                echo "<br><small>" . $loc['captcha_message'] . "</small>";
                                echo "<br><br><label for='code'>" . $loc['captcha_label'] . "</label>";
                                echo "<input type='hidden' id='code' name='code' value='" . $CODE_H . "'>";
                                echo "<br><input type='text' class='form-control' id='code_send' name='code_send' required>";
                                echo "<span class='invalid-feedback'>$captcha_err</span>";
                            }
                            ?>
                    </div>
                    <div class="form-group">
                        <br>
                        <input type="submit" class="btn btn-primary" value="<?php echo $loc['login']; ?>">
                        <a href='<?php echo $BASE_URL; ?>' class="btn btn-secondary">Cancel</a>
                    </div>
                    <hr>
                    <?php
                        if ($env['REGISTRATION_LOCKED'] === 'true') {
                            echo "<p>" . $loc['registration_closed'] . "</p>";
                        } else {
                            echo "<a class='btn btn-secondary form-control' href='$BASE_URL/staff/auth/register.php'>" . $loc['registration_open'] . "</a>";
                        }
                        ?>
                </form>
            </div>
        </div>
    </div>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_FOOTER.php";
    ?>