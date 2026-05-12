<?php
session_start();
include "../db.php";

// Only teachers allowed
if ($_SESSION['role'] !== 'teacher') {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $subject_id = $_POST['subject_id'];
    $duration = $_POST['duration'];
    $teacher_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published) 
                           VALUES (?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), ?, 0)");
    $stmt->execute([$subject_id, $title, $description, $teacher_id, $duration]);
    $success = "Exam created successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Exam - EaseExam</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .form-container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .form-container h2 { margin-bottom: 20px; }
    .form-container input, .form-container textarea, .form-container select {
      width: 100%; padding: 12px; margin-bottom: 15px;
      border: 1px solid #ccc; border-radius: 6px;
    }
    .form-container button {
      padding: 12px; width: 100%;
      background: #007bff; color: #fff;
      border: none; border-radius: 6px;
      font-size: 1rem; cursor: pointer;
    }
    .form-container button:hover { background: #0056b3; }
  </style>
</head>
<body>
  <?php include "../includes/teacher_sidebar.php"; ?>

  <div class="main">
    <div class="form-container">
      <h2>Create Exam</h2>
      <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
      <form method="POST">
        <input type="text" name="title" placeholder="Exam Title" required>
        <textarea name="description" placeholder="Exam Description" required></textarea>
        <select name="subject_id" required>
          <option value="">Select Subject</option>
          <?php
          $subjects = $pdo->query("SELECT * FROM subjects")->fetchAll();
          foreach ($subjects as $sub) {
              echo "<option value='{$sub['subject_id']}'>{$sub['name']}</option>";
          }
          ?>
        </select>
        <input type="number" name="duration" placeholder="Duration (minutes)" required>
        <button type="submit">Create Exam</button>
      </form>
    </div>
  </div>
</body>
</html>
