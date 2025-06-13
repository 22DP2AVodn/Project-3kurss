<?php
session_start();
require_once 'db.php';
date_default_timezone_set('Europe/Riga');

function showMessage($message, $isError = true) {
    $title = $isError ? 'Error' : 'Success';
    $textColor = $isError ? 'text-danger' : 'text-white';

    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>' . $title . ' - AstroSchool</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-dark text-light d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="text-center bg-secondary p-5 rounded shadow" style="max-width: 500px;">
            <h4 class="mb-3 ' . $textColor . '">' . $message . '</h4>
            <img src="uploads/homer.gif" alt="Feedback" class="mb-3" style="max-width: 100%; height: auto;">
            ' . (!$isError ? "<a href='login.php' class='btn btn-light w-100 mb-2'>Log In</a>" : '') . '
            <a href="index.php" class="btn btn-outline-light w-100">← Back to Home</a>
        </div>
    </body>
    </html>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"] ?? '';
    $password = $_POST["password"] ?? '';
    $confirm = $_POST["confirm"] ?? '';

    if (!$token || !$password || !$confirm) {
        showMessage("Please fill in all fields.");
    }

    if ($password !== $confirm) {
        showMessage("Passwords do not match.");
    }

    $stmt = $conn->prepare("SELECT user_id FROM reset_tokens WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        showMessage("The reset token is invalid or has expired.");
    }

    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $stmt->close();

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmt->bind_param("si", $hash, $user_id);

    if ($stmt->execute()) {
        $del = $conn->prepare("DELETE FROM reset_tokens WHERE token = ?");
        $del->bind_param("s", $token);
        $del->execute();
        $del->close();

        showMessage("Password successfully updated.", false);
    } else {
        showMessage("Failed to update password. Please try again.");
    }

    $stmt->close();
}
?>
