<?php
session_start();
include "../db.php";

// Only logged in users
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch top rankings (students with highest average scores)
$stmt = $pdo->query("
    SELECT u.full_name, ROUND(AVG(r.score),2) AS avg_score, COUNT(r.result_id) AS exams_taken
    FROM results r
    JOIN users u ON r.user_id = u.user_id
    WHERE u.role = 'student'
    GROUP BY u.user_id
    ORDER BY avg_score DESC
    LIMIT 20
");
$rankings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rankings - EaseExam</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
    h1 { text-align: center; color: #2a2af7; }
    table { width: 80%; margin: 20px auto; border-collapse: collapse; background: #fff; }
    th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
    th { background: #2a2af7; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
  </style>
</head>
<body>
  <h1>🏆 Student Rankings</h1>
  <table>
    <tr>
      <th>Rank</th>
      <th>Name</th>
      <th>Average Score</th>
      <th>Exams Taken</th>
    </tr>
    <?php $rank = 1; foreach ($rankings as $r): ?>
      <tr>
        <td><?php echo $rank++; ?></td>
        <td><?php echo htmlspecialchars($r['full_name']); ?></td>
        <td><?php echo $r['avg_score']; ?>%</td>
        <td><?php echo $r['exams_taken']; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
