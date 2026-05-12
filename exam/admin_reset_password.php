<?php
session_start();
include "../db.php";

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    if (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $stmt->execute([$password_hash, $user_id]);
        $success = "✅ Password reset successfully!";
    }
}

// Get all users (except admin)
$users = $pdo->query("SELECT user_id, full_name, email, role FROM users WHERE role != 'admin'")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin - Reset Password</title>
  <style>
    .reset-container { max-width: 600px; margin: 30px auto; padding: 20px; background:#fff; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
    th { background: #4f46e5; color:#fff; }
    .btn { padding: 6px 10px; background:#4f46e5; color:#fff; border:none; border-radius:5px; cursor:pointer; }
  </style>
</head>
<body>
<div class="reset-container">
  <h2>Admin Reset Password</h2>
  <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

  <table>
    <tr><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
    <?php foreach($users as $u): ?>
      <tr>
        <td><?= htmlspecialchars($u['full_name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['role'] ?></td>
        <td>
          <form method="POST" style="display:inline-block;">
            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" class="btn">Reset</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</body>
</html>
