<?php
session_start();
include "../db.php";

if ($_SESSION['role'] != 'student') {
    header("Location: ../dashboard.php");
    exit();
}

$stmt = $pdo->query("SELECT e.exam_id, e.title, s.name as subject
                     FROM exams e
                     JOIN subjects s ON e.subject_id = s.subject_id
                     WHERE e.is_published = 1");
$exams = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Available Exams</title>
  <style>
    :root { --primary:#4f46e5; --bg:#f5f7fb; --ink:#111827; --card:#ffffff; --muted:#6b7280; }
    body { margin:0; font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; background:var(--bg); color:var(--ink); }
    .page { max-width: 900px; margin: 32px auto; padding: 0 16px; }
    h2 { margin: 0 0 16px; font-weight: 700; color: var(--primary); }
    .list { list-style:none; padding:0; margin:0; display:grid; grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); gap:16px; }
    .item { background: var(--card); border:1px solid #e5e7eb; border-radius:12px; padding:16px; box-shadow: 0 6px 16px rgba(0,0,0,0.06); display:flex; justify-content:space-between; align-items:center; gap:12px; }
    .meta { display:flex; flex-direction:column; }
    .title { font-weight:600; }
    .subject { color: var(--muted); font-size:.9rem; }
    .start { text-decoration:none; background: var(--primary); color:#fff; padding:8px 12px; border-radius:8px; transition:.2s; white-space:nowrap; }
    .start:hover { filter: brightness(.95); transform: translateY(-1px); }
  </style>
  <link rel="stylesheet" href="../css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
<body>
  <div class="page">
    <h2>Available Exams</h2>
    <ul class="list">
      <?php foreach($exams as $exam): ?>
        <li class="item">
          <div class="meta">
            <span class="title"><?php echo htmlspecialchars($exam['title']); ?></span>
            <span class="subject"><?php echo htmlspecialchars($exam['subject']); ?></span>
          </div>
          <a class="start" href="start_exam.php?exam_id=<?php echo $exam['exam_id']; ?>">Start</a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</body>
</html>
