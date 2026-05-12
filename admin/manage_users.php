<?php
session_start();
include "../db.php";

// Only admin can access
if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

// Handle Delete User
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
}

// Handle Update Role
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE user_id = ?");
    $stmt->execute([$role, $user_id]);
}

// Handle Reset Password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    if (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $stmt->execute([$password_hash, $user_id]);
        $success = "Password reset successfully for user ID: $user_id";
    }
}

// Fetch all users
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Users - EaseExam</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
    th { background: #4f46e5; color: #fff; }
    form { display: inline; }
    input[type="password"] {
      padding: 5px; border: 1px solid #ccc; border-radius: 4px;
    }
    button {
      padding: 5px 10px; border: none; border-radius: 4px;
      background: #4f46e5; color: #fff; cursor: pointer;
    }
    button:hover { background: #372fba; }
    .msg { margin: 10px 0; padding: 10px; border-radius: 6px; }
    .error { background: #ffe5e5; color: red; }
    .success { background: #e5ffe5; color: green; }
    .back-container {
  margin-top: 30px;
  text-align: center;
}

.back-btn {
  display: inline-block;
  padding: 12px 28px;
  background: linear-gradient(135deg, #4f46e5, #6366f1);
  color: #fff;
  text-decoration: none;
  font-weight: 600;
  border-radius: 30px;
  box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.back-btn::after {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.2);
  transition: left 0.4s ease;
}

.back-btn:hover::after {
  left: 100%;
}

.back-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 25px rgba(79, 70, 229, 0.45);
}

  </style>
</head>
<body>
  <h2>Manage Users</h2>

  <?php if(isset($error)) echo "<p class='msg error'>$error</p>"; ?>
  <?php if(isset($success)) echo "<p class='msg success'>$success</p>"; ?>

  <table>
    <tr>
      <th>ID</th>
      <th>Full Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
    <?php foreach($users as $u): ?>
      <tr>
        <td><?php echo $u['user_id']; ?></td>
        <td><?php echo htmlspecialchars($u['full_name']); ?></td>
        <td><?php echo htmlspecialchars($u['email']); ?></td>
        <td>
          <form method="POST">
            <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
            <select name="role" onchange="this.form.submit()">
              <option value="student" <?php if($u['role']=='student') echo 'selected'; ?>>Student</option>
              <option value="teacher" <?php if($u['role']=='teacher') echo 'selected'; ?>>Teacher</option>
              <option value="admin" <?php if($u['role']=='admin') echo 'selected'; ?>>Admin</option>
            </select>
            <input type="hidden" name="update_role" value="1">
          </form>
        </td>
        <td><?php echo $u['created_at']; ?></td>
        <td>
          <!-- Delete -->
          <a href="?delete=<?php echo $u['user_id']; ?>" onclick="return confirm('Delete this user?');">Delete</a>
          <br><br>
          <!-- Reset Password -->
          <form method="POST" style="margin-top:5px;">
            <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" name="reset_password">Reset</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <br>
<div class="back-container">
  <a href="../dashboard.php" class="back-btn">
    ← Back to Dashboard
  </a>
</div>
</body>
</html>
