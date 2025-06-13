<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header('Location: index.php');
    exit;
}
include 'db.php';

$title = $_POST['title'];
$content = $_POST['content'];
$imagePath = null;

if (!empty($_FILES['image']['name'])) {
    $uploadDir = 'uploads/';
    $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
    $imagePath = $uploadDir . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
}

$stmt = $conn->prepare("INSERT INTO Posts (title, content, image) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $content, $imagePath);
$stmt->execute();
$stmt->close();

header("Location: post.php");
exit;
