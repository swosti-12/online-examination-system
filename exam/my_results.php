<?php
session_start();
include "../db.php";

// Only students allowed
if ($_SESSION['role'] !== 'student') {
    header("Location: ../dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT r.score, r.submitted_at, e.title, s.name AS subject 
                       FROM results r
                       JOIN exams e ON r.exam_id = e.exam_id
                       JOIN subjects s ON e.subject_id = s.subject_id
                       WHERE r.user_id = ?
                       ORDER BY r.submitted_at DESC");
$stmt->execute([$user_id]);
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Results - EaseExam</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    :root { --primary:#4f46e5; --bg:#f5f7fb; --ink:#111827; }
    body { background: var(--bg); color: var(--ink); }
    .main { padding: 24px; }
    .results-container {
      max-width: 900px;
      margin: 40px auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      border:1px solid #e5e7eb;
    }
    .results-container h2 { margin-bottom: 20px; text-align: center; color: var(--primary); }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #eef2f7;
      text-align: center;
    }
    th {
      background: #eef2ff;
      color: #1f2937;
      font-weight: 600;
    }
    tr:hover {
      background: #f1f1f1;
    }
    .pass { color: green; font-weight: bold; }
    .fail { color: red; font-weight: bold; }
  </style>
</head>
<body>
  <?php include "../includes/student_sidebar.php"; ?>

  <div class="main">
    <div class="results-container">
      <h2>My Results</h2>
      <?php if (count($results) > 0): ?>
        <table>
          <tr>
            <th>Exam Title</th>
            <th>Subject</th>
            <th>Score (%)</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
          <?php foreach ($results as $row): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['title']); ?></td>
              <td><?php echo htmlspecialchars($row['subject']); ?></td>
              <td><?php echo $row['score']; ?>%</td>
              <td class="<?php echo $row['score'] >= 40 ? 'pass' : 'fail'; ?>">
                <?php echo $row['score'] >= 40 ? 'Pass' : 'Fail'; ?>
              </td>
              <td><?php echo $row['submitted_at']; ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php else: ?>
        <p style="text-align:center;">No results available yet.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
