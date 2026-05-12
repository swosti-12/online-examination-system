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
<html>
<head>
  <title>View Results - EaseExam</title>
</head>
<body>
  <h2>View Results</h2>

  <form method="GET">
    <label>Select Exam:</label>
    <select name="exam_id" onchange="this.form.submit()">
      <option value="">-- Choose Exam --</option>
      <?php foreach($exams as $e): ?>
        <option value="<?php echo $e['exam_id']; ?>" <?php if(isset($_GET['exam_id']) && $_GET['exam_id'] == $e['exam_id']) echo 'selected'; ?>>
          <?php echo $e['title']; ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>

  <?php if(!empty($results)): ?>
    <h3>Results for Exam: <?php echo $exams[array_search($_GET['exam_id'], array_column($exams, 'exam_id'))]['title']; ?></h3>
    <table border="1" cellpadding="8">
      <tr>
        <th>Student Name</th>
        <th>Score</th>
        <th>Submitted At</th>
      </tr>
      <?php foreach($results as $r): ?>
        <tr>
          <td><?php echo $r['full_name']; ?></td>
          <td><?php echo $r['score']; ?></td>
          <td><?php echo $r['submitted_at']; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php elseif(isset($_GET['exam_id'])): ?>
    <p>No results found for this exam yet.</p>
  <?php endif; ?>

  <br>
  <a href="../dashboard.php">Back to Dashboard</a>
</body>
</html>
