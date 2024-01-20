<?php
echo "<h1>Redirecting</h1>";
echo "<p>If no redirect happens within 5 seconds click <a href='login.php'>here</a>.";
header("location: login.php");
?>
