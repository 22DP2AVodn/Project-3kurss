<?php
session_start();
include 'db.php';

// Only allow admins to delete posts
if (empty($_SESSION['is_admin'])) {
    die("Access denied.");
}

// Ensure post_id is passed and valid
if (!isset($_POST['post_id'])) {
    die("Missing post ID.");
}

$post_id = intval($_POST['post_id']);

// Start transaction to ensure both delete operations succeed together
$conn->begin_transaction();

try {
    // First, delete all comments associated with this post
    $deleteComments = $conn->prepare("DELETE FROM Comments WHERE post_id = ?");
    $deleteComments->bind_param("i", $post_id);
    $deleteComments->execute();
    $deleteComments->close();

    // Then delete the post itself
    $deletePost = $conn->prepare("DELETE FROM Posts WHERE id = ?");
    $deletePost->bind_param("i", $post_id);
    $deletePost->execute();
    $deletePost->close();

    // Commit transaction
    $conn->commit();

    // Redirect to posts page
    header("Location: post.php");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo "❌ Failed to delete post: " . $e->getMessage();
}
?>
