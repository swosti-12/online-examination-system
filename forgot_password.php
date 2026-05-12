<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = "❌ No account found with this email!";
    } elseif ($new_password !== $confirm_password) {
        // 2. Password mismatch
        $error = "❌ Passwords do not match!";
    } elseif (strlen($new_password) < 6) {
        // 3. Basic password strength check
        $error = "❌ Password must be at least 6 characters long!";
    } else {
        // 4. Update password
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $update->execute([$password_hash, $email]);

        $success = "✅ Password updated successfully! <a href='login.php'>Login now</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - EaseExam</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #4f46e5, #22d3ee);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .reset-container {
      background: #fff;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 380px;
      text-align: center;
    }
    .reset-container h2 { margin-bottom: 1.5rem; color: #4f46e5; }
    .form-group { margin-bottom: 1rem; text-align: left; }
    label { font-weight: bold; font-size: 0.9rem; display: block; margin-bottom: 0.4rem; }
    input {
      width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;
    }
    .btn {
      width: 100%; padding: 12px;
      background: #4f46e5; color: #fff; border: none;
      border-radius: 8px; cursor: pointer; font-weight: bold;
    }
    .btn:hover { background: #372fba; }
    .error { color: red; margin-bottom: 10px; font-weight: bold; }
    .success { color: green; margin-bottom: 10px; font-weight: bold; }
  </style>
</head>
<body>
  <div class="reset-container">
    <h2>Reset Password</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <form method="POST">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>New Password</label>
        <input type="password" name="new_password" required>
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>
      </div>
      <button type="submit" class="btn">Update Password</button>
    </form>
    <p><a href="login.php">Back to Login</a></p>
  </div>
</body>
</html>
