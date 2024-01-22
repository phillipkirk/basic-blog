<?php
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
    ?>

<!DOCTYPE html>
<html>
    <head>
            <title><?php echo $env['SITE_NAME']; ?></title>
            <link rel="icon" type="image/x-icon" href="../media/images/favicon.ico">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="<?php $BASE_URL; ?>/static/css/style.css" rel="stylesheet">
        <script src="https://cdn.tiny.cloud/1/0s2ce8pncoz89cjub19qhzublx61b2oqvq9h4gz9lc1roa4t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    </head>
    <body <?php if ($_SESSION['mode'] == "dark") { echo "class='bg-secondary text-light'"; } ?>>
        
    <?php
        if (isset($_SESSION['info'])) {
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
            echo $_SESSION['info'];
            unset($_SESSION['info']);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
        }
        ?>
        <nav class="navbar navbar-expand-lg <?php if ($_SESSION['mode'] == "light") { echo "navbar-light bg-light"; } else { echo "navbar-dark bg-dark text-light"; }?>">
            <div class="container-fluid">
                <a class="navbar-brand" href="../blog"><?php echo $env['SITE_NAME']; ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../blog"><?php echo $loc['home']; ?></a>
                        </li>
                        <?php
                            if (key_exists('username', $_SESSION)) {
                                echo '<li class="nav-item dropdown">';
                                    echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
                                        echo $_SESSION['username'];
                                    echo '</a>';
                                    echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                                        echo "<li><a class='dropdown-item' href='$BASE_URL/actions/new'>" . $loc['new_post'] . "</a></li>";
                                        echo '<li><hr class="dropdown-divider"></li>';
                                        echo "<li><a class='dropdown-item' href='$BASE_URL/auth/access_log'>" . $loc['access_log'] . "</a></li>";
                                        echo "<li><a class='dropdown-item' href='$BASE_URL/auth/password_reset'>" . $loc['reset_password'] . "</a></li>";
                                        echo "<li><a class='dropdown-item' href='$BASE_URL/auth/register'>" . $loc['create_new_user'] . "</a></li>";
                                        echo "<li><a class='dropdown-item' href='$BASE_URL/auth/logout'>" . $loc['logout'] . "</a></li>";
                                    echo '</ul>';
                                echo '</li>';
                            }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?php echo $BASE_URL; ?>/blog/post"><?php echo $loc['view_all_posts']; ?></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $loc['change_language']; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class='dropdown-item' href='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?lang=en'><?php echo $loc['english']; ?></a></li>
                                <li><a class='dropdown-item' href='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?lang=de'><?php echo $loc['german']; ?></a></li>
                                <li><a class='dropdown-item' href='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?lang=es'><?php echo $loc['spanish']; ?></a></li>
                                <li><a class='dropdown-item' href='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?lang=it'><?php echo $loc['italian']; ?></a></li>
                            </ul>
                        </li>
                        <span id='icon' style='margin-top: 10px; float: left;'>&#x1f4a1;</span>
                        <li class="form-check form-switch">
                            <form id='mode' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method='GET'>
                                <input class="form-check-input" onclick="formSubmit('mode')" type="checkbox" id="flexSwitchCheckDefault" style="margin-top: 15px;" <?php if ($_SESSION['mode'] == "dark") { echo "checked"; } ?>>
                                <input style='float: left;' type='hidden' name='mode_switch' value='clicked'>
                            </form>
                        </li>
                        <span id='icon' style='margin-top: 10px; float: left;'>&#x263E;</span>
                    </ul>
                    <?php
                        if ($env['SEARCH'] == 'on') {
                            echo '<form class="d-flex">';
                            echo '<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">';
                            echo '<button class="btn btn-outline-success" type="submit">Search</button>';
                            echo '</form>';
                        }
                        ?>
                </div>
            </div>
        </nav>
        <br>
        <div class='card <?php if ($_SESSION['mode'] == "dark") { echo "bg-dark text-light"; }?>' style="width: 90%; margin-left: 5%;">