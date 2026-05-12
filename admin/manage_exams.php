<?php
session_start();
include "../db.php";

// Only admin allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM exams WHERE exam_id = ?");
    $stmt->execute([$id]);
}

// Handle publish/unpublish
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE exams SET is_published = NOT is_published WHERE exam_id = ?");
    $stmt->execute([$id]);
}

// Fetch all exams with subject and creator
$stmt = $pdo->query("
    SELECT e.exam_id, e.title, e.duration_min, e.is_published, 
           s.name AS subject, u.full_name AS creator
    FROM exams e
    JOIN subjects s ON e.subject_id = s.subject_id
    JOIN users u ON e.created_by = u.user_id
    ORDER BY e.exam_id DESC
");
$exams = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Exams (Admin) - EaseExam</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
    h1 { text-align: center; color: #2a2af7; }
    a.button {
      display: inline-block; margin: 10px 0; padding: 10px 15px;
      background: #2a2af7; color: white; text-decoration: none; border-radius: 5px;
    }
    a.button:hover { background: #1a1adf; }
    table { width: 95%; margin: 20px auto; border-collapse: collapse; background: #fff; }
    th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
    th { background: #2a2af7; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    .status { font-weight: bold; }
    .published { color: green; }
    .unpublished { color: red; }
  </style>
</head>
<body>

  <h1>📑 Manage All Exams (Admin)</h1>

  <div style="text-align:center;">
    <a href="../exam/create_exam.php" class="button">➕ Create New Exam</a>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Subject</th>
      <th>Title</th>
      <th>Duration</th>
      <th>Created By</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php foreach($exams as $e): ?>
    <tr>
      <td><?php echo $e['exam_id']; ?></td>
      <td><?php echo htmlspecialchars($e['subject']); ?></td>
      <td><?php echo htmlspecialchars($e['title']); ?></td>
      <td><?php echo $e['duration_min']; ?> mins</td>
      <td><?php echo htmlspecialchars($e['creator']); ?></td>
      <td class="status <?php echo $e['is_published'] ? 'published' : 'unpublished'; ?>">
        <?php echo $e['is_published'] ? "Published" : "Unpublished"; ?>
      </td>
      <td>
        <a href="../exam/add_questions.php?exam_id=<?php echo $e['exam_id']; ?>">✏️ Add Questions</a> |
        <a href="?toggle=<?php echo $e['exam_id']; ?>">
          <?php echo $e['is_published'] ? "Unpublish" : "Publish"; ?>
        </a> |
        <a href="?delete=<?php echo $e['exam_id']; ?>" onclick="return confirm('Delete this exam?');">🗑 Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

</body>
</html>
