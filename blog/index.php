<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/templates/GLOBAL_HEADER.php'; ?>
        
            <div class="card-header">
                <h1><?php echo $loc['welcome']; ?></h1>
            </div>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/lib/templates/welcome.html"; ?>
        <br>
        <table class="table <?php if ($_SESSION['mode'] == "light") { echo "table-light"; } else { echo "table-dark"; } ?> table-striped" style="width: 90%; margin-left: 5%;">
            <thead>
                <tr>
                    <?php
                        if (key_exists('username', $_SESSION)) {
                            echo '<th colspan="5"><center><h4><strong>' . $loc['last_10_posts'] . '</strong></h4></center></th>';
                        } else {
                            echo '<th colspan="4"><center><h4><strong>' . $loc['last_10_posts'] . '</strong></h4></center></th>';
                        }
                        ?>
                </tr>
                <tr>
                    <th scope="col"><?php echo $loc['date']; ?></th>
                    <th scope="col"><?php echo $loc['subject']; ?></th>
                    <th scope="col"><?php echo $loc['author']; ?></th>
                    <?php
                        if (key_exists('username', $_SESSION)) {
                            echo '<th scope="col" colspan="2"></th>';
                        } else {
                            echo '<th scope="col"></th>';
                        }
                        ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM posts ORDER BY id DESC LIMIT 10";
                    $result = $link->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                                echo '<th scope="row">' . $row['created'] . '</th>';
                                echo '<td>' . $row['subject'] . '</td>';
                                echo '<td>'. $row['author'] . '</td>';
                                echo '<td><a class="btn btn-primary mb-3" href="post?pid=' . $row['id'] . '">' . $loc['view_post'] . '</a></td>';
                                if (key_exists('username', $_SESSION)) {
                                    echo "<td><form action='$BASE_URL/actions/delete' method='POST'><input type='hidden' name='pid' value='" . $row['id'] . "'><button type='submit' class='btn btn-danger mb-3'>" . $loc['delete'] . "</button></form></td>";
                                }
                            echo '</tr>';
                        }
                    }
                ?>
            </tbody>
        </table>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/templates/GLOBAL_FOOTER.php'; ?>