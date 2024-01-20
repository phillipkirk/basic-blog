<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/templates/GLOBAL_HEADER.php';
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: $BASE_URL/auth/");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['pid'])) {
        $pid = $_POST['pid'];
        $stmt = $link->prepare("DELETE FROM posts WHERE id=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->close();
        $_SESSION['info'] = "Post Deleted!";
        header("location: $BASE_URL");
    }
    ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/templates/GLOBAL_FOOTER.php'; ?>