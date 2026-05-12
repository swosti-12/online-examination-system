<?php
session_start();
include "../db.php";

// Only students allowed
if ($_SESSION['role'] !== 'student') {
    header("Location: ../dashboard.php");
    exit();
}

// Fetch all published exams
$stmt = $pdo->query("SELECT e.exam_id, e.title, e.description, e.duration_min, s.name as subject 
                     FROM exams e
                     JOIN subjects s ON e.subject_id = s.subject_id
                     WHERE e.is_published = 1
                     ORDER BY e.start_time DESC");
$exams = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Available Exams - EaseExam</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .exam-list {
      max-width: 900px;
      margin: 40px auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }
    .exam-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: 0.3s;
    }
    .exam-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    }
    .exam-card h3 {
      margin-bottom: 10px;
      color: #007bff;
    }
    .exam-card p {
      margin: 6px 0;
      color: #555;
    }
    .exam-card button {
      margin-top: 15px;
      padding: 10px 16px;
      background: #007bff;
      border: none;
      color: #fff;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    .exam-card button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <?php include "../includes/student_sidebar.php"; ?>

  <div class="main">
    <h2>Available Exams</h2>
    <div class="exam-list">
      <?php if (count($exams) > 0): ?>
        <?php foreach ($exams as $exam): ?>
          <div class="exam-card">
            <h3><?php echo htmlspecialchars($exam['title']); ?></h3>
            <p><strong>Subject:</strong> <?php echo htmlspecialchars($exam['subject']); ?></p>
            <p><?php echo htmlspecialchars($exam['description']); ?></p>
            <p><strong>Duration:</strong> <?php echo $exam['duration_min']; ?> minutes</p>
            <form action="attempt.php" method="GET">
              <input type="hidden" name="exam_id" value="<?php echo $exam['exam_id']; ?>">
              <button type="submit">Start Exam</button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No exams are available right now. Please check back later.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
