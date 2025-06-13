<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    die("Access denied.");
}
?>
<h2>Admin Dashboard</h2>
<a href='create_post.php'>Create New Post</a>
