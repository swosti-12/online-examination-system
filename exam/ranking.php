<?php
session_start();
include "../db.php";

// Only students and admin allowed
if (!in_array($_SESSION['role'], ['student', 'admin'])) {
    header("Location: ../dashboard.php");
    exit();
}

// Fetch rankings (unsorted) and then apply bubble sort
$stmt = $pdo->query("
    SELECT u.full_name, e.title AS exam_title, r.score, r.submitted_at
    FROM results r
    JOIN users u ON r.user_id = u.user_id
    JOIN exams e ON r.exam_id = e.exam_id
    WHERE u.role = 'student'
");
$rankings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bubble sort by score desc, then date asc
if ($rankings) {
  $n = count($rankings);
  for ($i = 0; $i < $n - 1; $i++) {
    for ($j = 0; $j < $n - $i - 1; $j++) {
      $a = $rankings[$j];
      $b = $rankings[$j + 1];
      $swap = false;
      if ((float)$a['score'] < (float)$b['score']) {
        $swap = true;
      } elseif ((float)$a['score'] == (float)$b['score'] && strtotime($a['submitted_at']) > strtotime($b['submitted_at'])) {
        $swap = true;
      }
      if ($swap) {
        $tmp = $rankings[$j];
        $rankings[$j] = $rankings[$j + 1];
        $rankings[$j + 1] = $tmp;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leaderboard - EaseExam</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    :root { --primary:#4f46e5; --bg:#f5f7fb; --ink:#111827; }
    body { background: var(--bg); color: var(--ink); }
    .main { padding: 24px; }
    .ranking-container {
      max-width: 900px;
      margin: 40px auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      border:1px solid #e5e7eb;
    }
    .ranking-container h2 { text-align: center; margin-bottom: 20px; color: var(--primary); }
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
    th { background: #eef2ff; color: #1f2937; font-weight:600; }
    tr:hover {
      background: #f1f1f1;
    }
    .rank {
      font-weight: bold;
      color: var(--primary);
    }
  </style>
</head>
<body>
  <?php
  if ($_SESSION['role'] == 'student') {
      include "../includes/student_sidebar.php";
  } else {
      include "../includes/admin_sidebar.php";
  }
  ?>

  <div class="main">
    <div class="ranking-container">
      <h2>Leaderboard</h2>
      <?php if (count($rankings) > 0): ?>
        <table>
          <tr>
            <th>Rank</th>
            <th>Student</th>
            <th>Exam</th>
            <th>Score (%)</th>
            <th>Date</th>
          </tr>
          <?php
          $rank = 1;
          foreach ($rankings as $row): ?>
            <tr>
              <td class="rank">#<?php echo $rank++; ?></td>
              <td><?php echo htmlspecialchars($row['full_name']); ?></td>
              <td><?php echo htmlspecialchars($row['exam_title']); ?></td>
              <td><?php echo $row['score']; ?>%</td>
              <td><?php echo $row['submitted_at']; ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php else: ?>
        <p style="text-align:center;">No rankings available yet.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
