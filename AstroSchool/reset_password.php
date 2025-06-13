<?php
session_start();
require_once 'db.php';

$token = $_GET['token'] ?? $_POST['token'] ?? '';

if (!$token) {
    die("❌ Invalid or missing token.");
}

// Validate token AND expiration
$stmt = $conn->prepare("SELECT user_id FROM reset_tokens WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Invalid or expired token.");
}

$row = $result->fetch_assoc();
$user_id = $row['user_id'];
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Reset Password - AstroSchool</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function toggleAllPasswords() {
      const icon = document.getElementById('toggle-icon');
      const inputs = document.querySelectorAll('input[data-type="password-toggle"]');
      const show = icon.getAttribute('data-visible') !== 'true';
      icon.setAttribute('data-visible', show ? 'true' : 'false');
      icon.textContent = show ? '🙈' : '👁️';
      inputs.forEach(input => input.type = show ? 'text' : 'password');
    }
  </script>
</head>
<body class="bg-dark text-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="card p-4 bg-secondary" style="width: 100%; max-width: 400px;">
    <h3 class="text-center">Set New Password</h3>
    <form method="POST" action="update_password.php">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <div class="mb-3">
        <label>New Password</label>
        <input type="password" name="password" class="form-control" data-type="password-toggle" required>
      </div>
      <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" name="confirm" class="form-control" data-type="password-toggle" required>
      </div>
      <div class="text-end mt-2 mb-3">
        <button type="button" class="btn btn-sm btn-outline-light" onclick="toggleAllPasswords()">
          <span id="toggle-icon" data-visible="false">👁️</span> Show/Hide Passwords
        </button>
      </div>
      <button type="submit" class="btn btn-light w-100">Reset Password</button>
    </form>
    <div class="mt-3 text-center">
      <a href="index.php" class="text-light">← Back to Home</a>
    </div>
  </div>
</div>
</body>
</html>
