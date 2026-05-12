<?php
session_start();
include "../db.php";

// Only teachers can access
if ($_SESSION['role'] != 'teacher') {
    header("Location: ../dashboard.php");
    exit();
}

// Fetch exams created by this teacher
$stmt = $pdo->prepare("SELECT * FROM exams WHERE created_by = ?");
$stmt->execute([$_SESSION['user_id']]);
$exams = $stmt->fetchAll();

// If teacher selects an exam
$results = [];
if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];

    $stmt = $pdo->prepare("SELECT r.result_id, u.full_name, r.score, r.submitted_at 
                           FROM results r
                           JOIN users u ON r.user_id = u.user_id
                           WHERE r.exam_id = ?");
    $stmt->execute([$exam_id]);
    $results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Results - EaseExam</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    :root { --primary:#4f46e5; --bg:#f5f7fb; --ink:#111827; }
    body { margin:0; font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; background:var(--bg); color:var(--ink); }
    .page { max-width: 1000px; margin: 32px auto; padding: 0 16px; }
    h2 { margin: 0 0 12px; color: var(--primary); font-weight:700; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px; box-shadow:0 6px 16px rgba(0,0,0,0.06); }
    .controls { display:flex; gap:12px; align-items:center; margin-bottom: 12px; }
    select { padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; }
    table { width:100%; border-collapse: collapse; }
    th, td { padding:12px; border-bottom:1px solid #eef2f7; text-align:left; }
    th { background:#eef2ff; font-weight:600; }
    tr:hover td { background:#fafbff; }
    .muted { color:#6b7280; }
    .back { display:inline-block; margin-top:12px; text-decoration:none; color:#fff; background:#4f46e5; padding:8px 12px; border-radius:8px; }
    .back:hover { filter:brightness(.95); }
  </style>
</head>
<body>
  <div class="page">
    <h2>View Results</h2>
    <div class="card">
      <form class="controls" method="GET">
        <label for="exam" class="muted">Select Exam:</label>
        <select id="exam" name="exam_id" onchange="this.form.submit()">
          <option value="">-- Choose Exam --</option>
          <?php foreach($exams as $e): ?>
            <option value="<?php echo $e['exam_id']; ?>" <?php if(isset($_GET['exam_id']) && $_GET['exam_id'] == $e['exam_id']) echo 'selected'; ?>>
              <?php echo htmlspecialchars($e['title']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>

      <?php if(!empty($results)): ?>
        <h3 class="muted">Results for Exam: <?php echo htmlspecialchars($exams[array_search($_GET['exam_id'], array_column($exams, 'exam_id'))]['title']); ?></h3>
        <table>
          <tr>
            <th>Student Name</th>
            <th>Score</th>
            <th>Submitted At</th>
          </tr>
          <?php foreach($results as $r): ?>
            <tr>
              <td><?php echo htmlspecialchars($r['full_name']); ?></td>
              <td><?php echo $r['score']; ?>%</td>
              <td><?php echo $r['submitted_at']; ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php elseif(isset($_GET['exam_id'])): ?>
        <p class="muted">No results found for this exam yet.</p>
      <?php endif; ?>
    </div>

    <a class="back" href="../dashboard.php">Back to Dashboard</a>
  </div>
</body>
</html>
