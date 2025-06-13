<?php
session_start();
require_once 'db.php';
$config = require 'config.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";
$alertType = "info";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if (!$user_id) {
        $message = "Email address not found.";
        $alertType = "danger";
    } else {
        $deleteStmt = $conn->prepare("DELETE FROM reset_tokens WHERE user_id = ?");
        $deleteStmt->bind_param("i", $user_id);
        $deleteStmt->execute();
        $deleteStmt->close();

        $token = bin2hex(random_bytes(32));
        $expiresAt = date("Y-m-d H:i:s", strtotime("+2 hour"));

        $insertStmt = $conn->prepare("INSERT INTO reset_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $insertStmt->bind_param("iss", $user_id, $token, $expiresAt);
        $insertStmt->execute();
        $insertStmt->close();

        $resetLink = "http://localhost/AstroSchool/reset_password.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp_user'];
            $mail->Password = $config['smtp_pass'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $config['smtp_port'];

            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($email);
            $mail->Subject = 'AstroSchool Password Reset';
            $mail->Body = "Hello,\n\nTo reset your password, click the link below:\n\n$resetLink\n\nThis link will expire in 1 hour.\n\nIf you did not request a password reset, please ignore this email.";

            $mail->send();
            $message = "Reset link has been sent to your email.";
            $alertType = "success";
        } catch (Exception $e) {
            $message = "Email sending failed: " . $mail->ErrorInfo;
            $alertType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - AstroSchool</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="card p-4 bg-secondary" style="width: 100%; max-width: 400px;">
    <h3 class="text-center">Reset Password</h3>
    <?php if ($message): ?>
      <div class="alert alert-<?= $alertType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-light w-100">Send Reset Link</button>
    </form>
    <div class="mt-3 text-center">
      <a href="login.php" class="text-light">Back to Login</a>
    </div>
  </div>
</div>
</body>
</html>
