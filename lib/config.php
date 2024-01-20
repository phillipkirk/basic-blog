<?php
    // Include the .env config
    $env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/lib/.env');

    // Define base URL
    if ($env['ENV'] == 'production') {
        $BASE_URL = $env['BASE_URL'];
    } else {
        $BASE_URL = $env['BASE_URL_TESTING'];
    } 

    // Set the session cookie parameters
    $cookieParams = session_get_cookie_params();
    $cookieParams['lifetime'] = $env['COOKIE_TIMEOUT'];
    $cookieParams['samesite'] = 'Strict';
    if ($env['ENV'] === 'production') {
        $cookieParams['secure'] = true;
    }
    session_set_cookie_params($cookieParams);
    session_name($env['COOKIE_NAME']);

    // Start the session
    session_start();

    // Set language localisation
    if (isset($_GET['lang'])) {
        $_SESSION['lang'] = $_GET['lang'];
    } elseif (!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = 'en';
    }
    include $_SERVER['DOCUMENT_ROOT'] . "/lib/lang/" . $_SESSION['lang'] . ".php";

    // Regenerate the session ID
    session_regenerate_id();

    // Define database credentials
    define('DB_SERVER', $env['DB_SERVER']);
    define('DB_USERNAME', $env['DB_USERNAME']);
    define('DB_PASSWORD', $env['DB_PASSWORD']); 
    define('DB_NAME', $env['DB_NAME']);

    // Connect to database
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }


    // Define CAPTCHA state
    if ($env['CAPTCHA'] == "true") {
        $CAPTCHA = 'on';
    } else {
        $CAPTCHA = '0ff';
    }
    ?>