<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_HEADER.php";
    $_SESSION = array();
    session_destroy();
    header("location: $BASE_URL/auth/login");
    exit;
    ?>