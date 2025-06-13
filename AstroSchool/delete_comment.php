<?php
session_start();
include 'db.php';

// Only allow admin users
if (empty($_SESSION['is_admin'])) {
    die("Access denied.");
}

// Validate and get the comment ID and post ID from the form
if (isset($_POST['comment_id'], $_POST['post_id'])) {
    $comment_id = intval($_POST['comment_id']);
    $post_id = intval($_POST['post_id']);

    // Delete comment
    $stmt = $conn->prepare("DELETE FROM Comments WHERE id = ?");
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        // Redirect back to the post page after deletion
        header("Location: post.php?id=" . $post_id);
        exit;
    } else {
        echo "Failed to delete comment.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
