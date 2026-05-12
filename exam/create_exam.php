<?php
session_start();
include "../db.php";

// Only teachers allowed
if ($_SESSION['role'] !== 'teacher') {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        http_response_code(400);
        die('Invalid CSRF token');
    }
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
    :root { --primary:#4f46e5; --primary-dark:#372fba; --bg:#f5f7fb; --text:#111827; }
    body { background: var(--bg); color: var(--text); }
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
      background: var(--primary); color: #fff;
      border: none; border-radius: 6px;
      font-size: 1rem; cursor: pointer;
    }
    .form-container button:hover { background: var(--primary-dark); }
  </style>
</head>
<body>
  <?php include "../includes/teacher_sidebar.php"; ?>


    <div class="form-container">
      <h2>Create Exam</h2>
      <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
      <form method="POST">
        <!-- CSRF token-(prevent web from unauthorised requests) -->
        <?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">    
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
</body>
</html>
