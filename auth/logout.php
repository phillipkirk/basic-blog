<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_HEADER.php";
    $_SESSION = array();
    session_destroy();
    ?>

<div class='content'>
    <div class="card">
        <div class="card-header">
            <h2>Logged Out</h2>
        </div>
        <div class="card-body">
            <p>You have been successfully logged out.</p>
            <a href='<?php echo $BASE_URL; ?>' class="btn btn-secondary">Return Home</a>
        </div>
    </div>
</div>