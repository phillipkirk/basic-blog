<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/templates/GLOBAL_HEADER.php';
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: $BASE_URL/auth/");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $subject = $_POST['subject'];
        $content = trim($_POST['content']);
        $author = $_SESSION['username'];
        $stmt = $link->prepare("INSERT INTO posts (subject, content, author) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $subject, $content, $author);
        $stmt->execute();
        $stmt->close();
        $info = "Post Saved!";
    }
    ?>
<div class='card' style="width: 90%; margin-left: 5%;">
    <?php
        if (isset($info)) {
            echo "<svg xmlns='http://www.w3.org/2000/svg' style='display: none;'>";
            echo "<symbol id='check-circle-fill' fill='currentColor' viewBox='0 0 16 16'>";
            echo "<path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>";
            echo "</symbol>";
            echo "<symbol id='exclamation-triangle-fill' fill='currentColor' viewBox='0 0 16 16'>";
            echo "<path d='M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z'/>";
            echo "</symbol>";
            echo "</svg>";
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
            echo "<svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>";
            echo htmlspecialchars($info);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
        }
        ?>
    <div class="card-header">
        <h1>New Post</h1>
    </div>
    <div>
        <form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>' method='POST'>
            <div class='form-group'>
                <label class='form-label' for='subject'>Subject: </label>
                <br>
                <input type='text' id='subject' name='subject' class='form-control' required>
            </div>
            
            <div class='form-group'>
                <label class='form-label' for='content'>Content: </label>
                <br>
                <textarea type='text' id='content' name='content'></textarea>
            </div>
            <br>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/templates/GLOBAL_FOOTER.php'; ?>