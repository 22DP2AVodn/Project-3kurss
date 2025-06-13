<?php
include 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        $message = "❌ Passwords do not match.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO Users (username, email, password_hash) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $username, $email, $hash);
            if ($stmt->execute()) {
                $message = "✅ Registration successful!";
            } else {
                $message = "❌ Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Database error.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - AstroSchool</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function toggleAllPasswords() {
      const icon = document.getElementById('toggle-icon');
      const inputs = document.querySelectorAll('input[data-type="password-toggle"]');
      const show = icon.getAttribute('data-visible') !== 'true';
      icon.setAttribute('data-visible', show ? 'true' : 'false');
      icon.textContent = show ? '🙈' : '👁️';
      inputs.forEach(input => {
        input.type = show ? 'text' : 'password';
      });
    }
  </script>
</head>
<body class="bg-dark text-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="card p-4 bg-secondary" style="width: 100%; max-width: 500px;">
    <h3 class="text-center">Register</h3>
    <?php if ($message) echo "<div class='alert alert-info'>$message</div>"; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
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
      <button type="submit" class="btn btn-light w-100">Register</button>
    </form>
    <div class="mt-3 text-center">
      <a href="login.php" class="text-light">Already registered? Log in</a><br>
      <a href="index.php" class="text-light">← Back to Home</a>
    </div>
  </div>
</div>
</body>
</html>
