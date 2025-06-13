<?php
session_start();
include 'db.php';

// Check if admin
if (empty($_SESSION["is_admin"])) {
    die("Access denied.");
}

$message = "";

// Handle post submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $theme = $_POST["theme"] ?? 'Astronomy';
    $user_id = $_SESSION["user_id"];

    // Handle image upload
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploads/';
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // Insert post into database
    $stmt = $conn->prepare("INSERT INTO Posts (user_id, title, content, image, theme) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $content, $imagePath, $theme);
    $stmt->execute();

    $message = "Post created successfully.";
}
?>

<?php include 'header.php'; ?>

<main class="container mt-5">
    <h2 class="mb-4 text-center">Create a New Post</h2>

    <?php if ($message): ?>
        <div class="alert alert-success text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="bg-secondary text-light p-4 rounded">
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input name="title" id="title" class="form-control" required placeholder="Post title...">
        </div>

        <div class="mb-3">
            <label for="theme" class="form-label">Category:</label>
            <select name="theme" id="theme" class="form-select" style="width: 150px;">
                <option value="Both">Both</option>
                <option value="Astronomy">Astronomy</option>
                <option value="Astrophotography">Astrophotography</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Upload Image:</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content:</label>
            <textarea name="content" id="content" rows="10" class="form-control" required placeholder="Write your post here..."></textarea>
        </div>

        <button type="submit" class="btn btn-info">Publish Post</button>
    </form>
</main>

<?php include 'footer.php'; ?>
