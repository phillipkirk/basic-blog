<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/templates/GLOBAL_HEADER.php';
    if ($_GET && isset($_GET['pid'])) {
        $pid = $_GET['pid'];
        $sql = "SELECT * FROM posts where id=$pid";
        $result = $link->query($sql);
        if ($result->num_rows > 0) {
            while($post = $result->fetch_assoc()) {
                echo "<div class='card' id='" . $post['id'] ."'><div class='card-header bg-secondary'><h3>" . $loc['post_subject'] . ": " . $post['subject'] . "</h3></div>";
                echo "<div class='card-body bg-light text-dark'><h5 class='card-title'>" . $loc['posted_by'] . ": <u>" . $post['author'] . "</u> " . $loc['on'] . ": <u>" . $post['created'] . "</u></h5><br>";
                echo $post['content'];
                echo "</div></div><br>";
            }
        }
    } else {
        $sql = "SELECT * FROM posts";
        $result = $link->query($sql);
        if ($result->num_rows > 0) {
            while($post = $result->fetch_assoc()) {
                if ($_SESSION['mode'] == 'light') {
                    $class = "card";
                } else {
                    $class = "card bg-dark text-light";
                }
                echo "<div class='$class' id='" . $post['id'] ."'><div class='card-header'><h3>" . $loc['post_subject'] . ": " . $post['subject'] . "</h3></div>";
                echo "<div class='card-body'><h5 class='card-title'>" . $loc['posted_by'] . ": <u>" . $post['author'] . "</u> " . $loc['on'] . ": <u>" . $post['created'] . "</u></h5><br>";
                echo $post['content'];
                echo "</div></div><br>";
            }
        }
    }
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/templates/GLOBAL_FOOTER.php';
    ?>