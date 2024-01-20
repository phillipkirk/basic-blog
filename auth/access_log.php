<?php
    
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_HEADER.php";

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: $BASE_URL/auth/");
        exit;
    }
    ?>

<div class='content'>
    <center>
        <h1><?php echo $loc['access_log']; ?></h1>
    </center>
    <a class="btn btn-danger" style='position: absolute; right: 5px;' href="#" role="button"><?php echo $loc['clear_log']; ?></a>
    <?php
        $sql = "SELECT * FROM login_log";
        $return = $link->query($sql);
        echo "<table class='table table-striped table-hover'>";
        echo "<thead><th scope='col'>" . $loc['username'] . "</th><th scope='col'>" . $loc['status'] . "</th><th scope='col'>" . $loc['timestamp'] . "</th></thead>";
        echo "<tbody>";
        if ($return->num_rows > 0) {
            while($log = $return->fetch_assoc()) {
                if ($log['status'] !== 'FAIL') {
                    $class = 'table-success';
                } else {
                    $class = 'table-danger';
                }
                echo "<tr><td class='$class'>" . $log['username'] . "</td><td class='$class'>" . $log['status'] . "</td><td class='$class'>" . $log['timestamp'] . "</td></tr>";
            }
        }
        echo "</tbody>";
        echo "</table>"
        ?>

</div>
<?php
    
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/GLOBAL_FOOTER.php";
    ?>