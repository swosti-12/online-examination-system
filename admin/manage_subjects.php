<?php
session_start();
include "../db.php";

// Only admin can access
if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

// Add new subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    if ($name != "") {
        $stmt = $pdo->prepare("INSERT INTO subjects (name) VALUES (?)");
        $stmt->execute([$name]);
    }
}

// Delete subject
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE subject_id = ?");
    $stmt->execute([$id]);
}

// Fetch all subjects
$subjects = $pdo->query("SELECT * FROM subjects ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Subjects - EaseExam</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
    h1 { text-align: center; color: #2a2af7; }
    table { width: 60%; margin: 20px auto; border-collapse: collapse; background: #fff; }
    th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
    th { background: #2a2af7; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    form { text-align: center; margin-bottom: 20px; }
    input[type="text"] { padding: 8px; width: 250px; }
    button { padding: 8px 15px; background: #2a2af7; color: white; border: none; border-radius: 4px; }
    button:hover { background: #1a1adf; }
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
  <h1>📚 Manage Subjects</h1>

  <form method="POST">
    <input type="text" name="name" placeholder="Enter subject name" required>
    <button type="submit" name="add">Add Subject</button>
  </form>

  <table>
    <tr>
      <th>ID</th>
      <th>Subject Name</th>
      <th>Actions</th>
    </tr>
    <?php foreach($subjects as $s): ?>
    <tr>
      <td><?php echo $s['subject_id']; ?></td>
      <td><?php echo htmlspecialchars($s['name']); ?></td>
      <td>
        <a href="?delete=<?php echo $s['subject_id']; ?>" onclick="return confirm('Delete this subject?');">🗑 Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <div class="back-container">
  <a href="../dashboard.php" class="back-btn">
    ← Back to Dashboard
  </a>
</div>
</body>
</html>
