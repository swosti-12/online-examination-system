<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$name = "User";

// Get full name
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
  <title>Dashboard - EaseExam</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f9fafb;
      display: flex;
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      background: #1f2937; /* dark gray */
      color: #fff;
      height: 100vh;
      position: fixed;
      padding: 20px;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #22d3ee;
    }

    .sidebar p {
      text-align: center;
      font-size: 0.9rem;
      margin-bottom: 20px;
    }

    .sidebar a {
      display: block;
      padding: 12px;
      color: #fff;
      text-decoration: none;
      margin-bottom: 8px;
      border-radius: 6px;
      transition: background 0.3s;
    }

    .sidebar a i {
      margin-right: 8px;
    }

    .sidebar a:hover {
      background: #374151;
    }

    /* Main content */
    .main {
      margin-left: 240px;
      padding: 25px;
      flex: 1;
    }

    .main h1 {
      margin-bottom: 20px;
      color: #4f46e5;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .card {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.08);
      text-align: center;
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-6px);
    }

    .card i {
      font-size: 2rem;
      color: #4f46e5;
      margin-bottom: 10px;
    }

    .card h3 {
      margin-bottom: 10px;
      font-size: 1.1rem;
    }

    .card p {
      font-size: 1.4rem;
      font-weight: bold;
      color: #22d3ee;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>EaseExam</h2>
    <p>Hi, <?php echo htmlspecialchars($name); ?> <br>(<?php echo ucfirst($role); ?>)</p>
    <hr>

    <?php if ($role == "student"): ?>
      <a href="exam/start_exam.php"><i class="fa-solid fa-pen"></i> Take Exam</a>
      <a href="exam/my_results.php"><i class="fa-solid fa-chart-line"></i> My Results</a>
      <a href="exam/ranking.php"><i class="fa-solid fa-trophy"></i> Rankings</a>

    <?php elseif ($role == "teacher"): ?>
      <a href="exam/create_exam.php"><i class="fa-solid fa-plus"></i> Create Exam</a>
      <a href="exam/add_questions.php"><i class="fa-solid fa-question"></i> Add Questions</a>
      <a href="exam/view_results.php"><i class="fa-solid fa-file-lines"></i> View Results</a>

    <?php elseif ($role == "admin"): ?>
      <a href="admin/manage_subjects.php"><i class="fa-solid fa-book"></i> Manage Subjects</a>
      <a href="admin/manage_users.php"><i class="fa-solid fa-users"></i> Manage Users</a>
      <a href="exam/ranking.php"><i class="fa-solid fa-trophy"></i> Rankings</a>
      <a href="admin/admin_reset_password.php"><i class="fa-solid fa-key"></i> Reset Passwords</a>
    <?php endif; ?>

    <hr>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>

  <!-- Main content -->
  <div class="main">
    <h1>Dashboard</h1>
    <div class="cards">
      <?php if ($role == "student"): ?>
        <div class="card"><i class="fa-solid fa-book-open"></i><h3>Upcoming Exams</h3><p>2</p></div>
        <div class="card"><i class="fa-solid fa-star"></i><h3>Last Score</h3><p>85%</p></div>
        <div class="card"><i class="fa-solid fa-ranking-star"></i><h3>Rank</h3><p>#5</p></div>

      <?php elseif ($role == "teacher"): ?>
        <div class="card"><i class="fa-solid fa-book"></i><h3>Exams Created</h3><p>5</p></div>
        <div class="card"><i class="fa-solid fa-users"></i><h3>Students Attempted</h3><p>120</p></div>
        <div class="card"><i class="fa-solid fa-tasks"></i><h3>Pending Reviews</h3><p>3</p></div>

      <?php elseif ($role == "admin"): ?>
        <div class="card"><i class="fa-solid fa-users"></i><h3>Total Users</h3><p>340</p></div>
        <div class="card"><i class="fa-solid fa-book"></i><h3>Subjects</h3><p>12</p></div>
        <div class="card"><i class="fa-solid fa-file"></i><h3>Exams</h3><p>25</p></div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
