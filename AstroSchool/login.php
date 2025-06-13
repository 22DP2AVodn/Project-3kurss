<?php
session_start();
include 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hash, $role);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            $_SESSION["is_admin"] = ($role === 'admin'); // ✅ admin detection

            header("Location: index.php");
            exit;
        } else {
            $message = "❌ Invalid password.";
        }
    } else {
        $message = "❌ Email not found.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - AstroSchool</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #111;
      color: white;
    }
    .card {
      background-color: #2c2f33;
      border: 1px solid #444;
    }
    .form-label {
      color: white;
    }
  </style>
  <script>
    function toggleVisibility(id, iconId) {
      const input = document.getElementById(id);
      const icon = document.getElementById(iconId);
      if (input.type === "password") {
        input.type = "text";
        icon.textContent = "🙈";
      } else {
        input.type = "password";
        icon.textContent = "👁️";
      }
    }
  </script>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="card p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center text-light">Login</h3>
    <?php if ($message): ?>
      <div class="alert alert-warning text-center"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" name="password" id="password" class="form-control" required>
          <button type="button" onclick="toggleVisibility('password', 'toggle-password')" class="btn btn-outline-light">
            <span id="toggle-password">👁️</span>
          </button>
        </div>
      </div>
      <button type="submit" class="btn btn-info w-100 mt-2">Login</button>
    </form>
    <div class="mt-3 text-center">
      <a href="forgot_password.php" class="text-light">Forgot Password?</a><br>
      <a href="register.php" class="text-light">Need an account? Register</a><br>
      <a href="index.php" class="text-light">← Back to Home</a>
    </div>
  </div>
</div>
</body>
</html>
