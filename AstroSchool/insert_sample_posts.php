<?php
include 'db.php';

// Delete existing posts
$conn->query("DELETE FROM Posts");

// Create a user if none exists
$check_user = $conn->query("SELECT id FROM Users LIMIT 1");
if ($check_user->num_rows == 0) {
    $conn->query("INSERT INTO Users (username, email, password_hash, role) VALUES ('demo', 'demo@example.com', '" . password_hash("demo123", PASSWORD_DEFAULT) . "', 'admin')");
    $user_id = $conn->insert_id;
} else {
    $user_id = $check_user->fetch_assoc()['id'];
}

// Post 1
$content1 = <<<HTML
<img src="uploads/deep-sky-astrophotography-how-to.jpg" width="100%" />
<p>This comprehensive guide walks beginners through the process of capturing deep-sky images using a camera, telescope, and tracking mount. It covers equipment setup, polar alignment, and image processing techniques.</p>
HTML;

// Post 2
$content2 = <<<HTML
<img src="uploads/orion-nebula-photography.jpg" width="100%" />
<p>This article provides step-by-step instructions for capturing the Orion Nebula, one of the most spectacular deep-sky objects. It includes tips on camera settings, exposure times, and post-processing techniques.</p>
HTML;

// Post 3
$content3 = <<<HTML
<img src="uploads/astrophotography-image-processing.jpg" width="100%" />
<p>This tutorial delves into the image processing workflow for deep-sky astrophotography using Adobe Photoshop. It covers stacking, gradient removal, and color enhancement to produce stunning final images.</p>
HTML;

// Insert posts
$stmt = $conn->prepare("INSERT INTO Posts (user_id, title, content) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $title, $content);

// Post 1
$title = "Deep-Sky Astrophotography How-To";
$content = $content1;
$stmt->execute();

// Post 2
$title = "How to Photograph the Orion Nebula";
$content = $content2;
$stmt->execute();

// Post 3
$title = "Astrophotography Image Processing in Photoshop";
$content = $content3;
$stmt->execute();

echo "✅ Posts inserted!";
?>
