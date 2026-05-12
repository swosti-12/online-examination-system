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
<html>
<head>
  <title>Available Exams</title>
</head>
<body>
  <h2>Available Exams</h2>
  <ul>
    <?php foreach($exams as $exam): ?>
      <li>
        <?php echo $exam['title'] . " (" . $exam['subject'] . ")"; ?>
        <a href="start_exam.php?exam_id=<?php echo $exam['exam_id']; ?>">Start</a>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
