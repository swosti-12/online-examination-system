<?php
session_start();
include "../db.php";

if ($_SESSION['role'] !== 'teacher') {
  header("Location: ../dashboard.php");
  exit();
}

$teacherId = $_SESSION['user_id'];

// Handle publish/unpublish
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $examId = (int)($_POST['exam_id'] ?? 0);
  $action = $_POST['action'] ?? '';
  if ($examId > 0 && in_array($action, ['publish','unpublish','delete'])) {
    // Verify ownership
    $own = $pdo->prepare("SELECT exam_id FROM exams WHERE exam_id = ? AND created_by = ?");
    $own->execute([$examId, $teacherId]);
    if ($own->fetch()) {
      if ($action === 'delete') {
        $pdo->prepare("DELETE FROM exams WHERE exam_id = ?")->execute([$examId]);
      } else {
        $is = $action === 'publish' ? 1 : 0;
        $pdo->prepare("UPDATE exams SET is_published = ? WHERE exam_id = ?")->execute([$is, $examId]);
      }
    }
  }
}

$stmt = $pdo->prepare("SELECT e.*, s.name AS subject FROM exams e JOIN subjects s ON e.subject_id = s.subject_id WHERE e.created_by = ? ORDER BY e.start_time DESC");
$stmt->execute([$teacherId]);
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Exams - EaseExam</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .container { max-width: 1000px; margin: 30px auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.1); }
    table { width:100%; border-collapse: collapse; }
    th, td { padding:12px; border-bottom:1px solid #eee; text-align:center; }
    th { background:#2c3e50; color:#fff; }
    form.inline { display:inline-block; margin:0 4px; }
    .btn { border:none; padding:8px 12px; border-radius:6px; cursor:pointer; color:#fff; }
    .pub { background:#10b981; }
    .unpub { background:#6b7280; }
    .del { background:#ef4444; }
  </style>
  </head>
<body>
  <?php include "../includes/teacher_sidebar.php"; ?>
  <div class="main">
    <div class="container">
      <h2>My Exams</h2>
      <table>
        <tr>
          <th>Title</th>
          <th>Subject</th>
          <th>Duration</th>
          <th>Published</th>
          <th>Actions</th>
        </tr>
        <?php foreach ($exams as $e): ?>
          <tr>
            <td><?php echo htmlspecialchars($e['title']); ?></td>
            <td><?php echo htmlspecialchars($e['subject']); ?></td>
            <td><?php echo (int)$e['duration_min']; ?> min</td>
            <td><?php echo $e['is_published'] ? 'Yes' : 'No'; ?></td>
            <td>
              <?php if ($e['is_published']): ?>
                <form method="POST" class="inline"><input type="hidden" name="exam_id" value="<?php echo $e['exam_id']; ?>"><input type="hidden" name="action" value="unpublish"><button class="btn unpub" type="submit">Unpublish</button></form>
              <?php else: ?>
                <form method="POST" class="inline"><input type="hidden" name="exam_id" value="<?php echo $e['exam_id']; ?>"><input type="hidden" name="action" value="publish"><button class="btn pub" type="submit">Publish</button></form>
              <?php endif; ?>
              <form method="POST" class="inline" onsubmit="return confirm('Delete this exam?');"><input type="hidden" name="exam_id" value="<?php echo $e['exam_id']; ?>"><input type="hidden" name="action" value="delete"><button class="btn del" type="submit">Delete</button></form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</body>
</html>


