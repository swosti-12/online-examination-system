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
</body>
</html>
