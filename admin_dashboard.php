<?php
session_start();
include "db.php"; 
error_log("=== ADMIN DASHBOARD ACCESS ===");
error_log("Session ID: " . session_id());
error_log("User ID: " . ($_SESSION['user_id'] ?? 'NOT_SET'));
error_log("User Role: " . ($_SESSION['role'] ?? 'NOT_SET'));

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    error_log("ACCESS DENIED - Redirecting to login");
    header("Location: login.php");
    exit();
}

// Fetch counts for cards
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalSubjects = $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
$totalExams = $pdo->query("SELECT COUNT(*) FROM exams")->fetchColumn();

// Fetch user role distribution
$roles = $pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role")->fetchAll(PDO::FETCH_ASSOC);

// Fetch exams per subject
$examsPerSubject = $pdo->query("
    SELECT s.name as subject, COUNT(e.exam_id) as total 
    FROM subjects s 
    LEFT JOIN exams e ON s.subject_id = e.subject_id 
    GROUP BY s.subject_id
")->fetchAll(PDO::FETCH_ASSOC);

// Admin name
$name = "Admin";
$stmt = $pdo->prepare("SELECT full_name FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
if ($row = $stmt->fetch()) {
    $name = $row['full_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - EaseExam</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      color: #333;
      display: flex;
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      background: #2c3e50;
      color: #fff;
      height: 100vh;
      padding-top: 20px;
      position: fixed;
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 10px;
    }
    .sidebar p {
      text-align: center;
      font-size: 0.9rem;
      margin-bottom: 20px;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: #fff;
      text-decoration: none;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background: #34495e;
    }

    /* Main */
    .main {
      margin-left: 240px;
      padding: 20px;
      flex: 1;
    }
    .main h1 {
      margin-bottom: 20px;
      color: #2a2af7;
    }

    /* Cards */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }
    .card {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      text-align: center;
      transition: 0.3s;
    }
    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }
    .card h3 {
      font-size: 1.1rem;
      margin-bottom: 10px;
      color: #444;
    }
    .card p {
      font-size: 2rem;
      font-weight: bold;
      color: #2a2af7;
    }

    .charts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 30px;
    }

    .chart-container {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    footer {
      text-align: center;
      padding: 15px;
      font-size: 0.9rem;
      color: #888;
      margin-top: 40px;
      border-top: 1px solid #ddd;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>EaseExam</h2>
    <p>Hi, <?php echo htmlspecialchars($name); ?></p>
    <a href="admin/manage_users.php">👥 Manage Users</a>
    <a href="admin/manage_subjects.php">📚 Manage Subjects</a>
    <a href="exam/create_exam.php">➕ Create Exam</a>
    <a href="exam/view_results.php">📑 View Results</a>
    <a href="exam/ranking.php">🏆 Rankings</a>
    <hr style="border-color: rgba(255,255,255,0.2);">
    <a href="logout.php">🚪 Logout</a>
  </div>

  <!-- Main -->
  <div class="main">
    <h1>Admin Dashboard</h1>

    <!-- Summary Cards -->
    <div class="cards">
      <div class="card"><h3>Total Users</h3><p><?php echo $totalUsers; ?></p></div>
      <div class="card"><h3>Total Subjects</h3><p><?php echo $totalSubjects; ?></p></div>
      <div class="card"><h3>Total Exams</h3><p><?php echo $totalExams; ?></p></div>
    </div>

    <!-- Charts -->
    <div class="charts">
      <div class="chart-container">
        <h3>User Roles Distribution</h3>
        <canvas id="rolesChart"></canvas>
      </div>
      <div class="chart-container">
        <h3>Exams per Subject</h3>
        <canvas id="examsChart"></canvas>
      </div>
    </div>

    <footer>
      &copy; <?php echo date("Y"); ?> EaseExam. All rights reserved.
    </footer>
  </div>

<script>
  // User Roles Data
  const roleLabels = <?php echo json_encode(array_column($roles, 'role')); ?>;
  const roleCounts = <?php echo json_encode(array_column($roles, 'count')); ?>;

  new Chart(document.getElementById('rolesChart'), {
    type: 'pie',
    data: {
      labels: roleLabels,
      datasets: [{
        label: 'Users',
        data: roleCounts,
        backgroundColor: ['#2a2af7', '#22d3ee', '#ff6384']
      }]
    }
  });

  // Exams per Subject Data
  const subjectLabels = <?php echo json_encode(array_column($examsPerSubject, 'subject')); ?>;
  const subjectCounts = <?php echo json_encode(array_column($examsPerSubject, 'total')); ?>;

  new Chart(document.getElementById('examsChart'), {
    type: 'bar',
    data: {
      labels: subjectLabels,
      datasets: [{
        label: 'Exams',
        data: subjectCounts,
        backgroundColor: '#2a2af7'
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>

</body>
</html>
