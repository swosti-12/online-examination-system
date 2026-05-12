<?php
session_start();
include "../db.php";

// Only teachers allowed
if ($_SESSION['role'] !== 'teacher') {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exam_id = $_POST['exam_id'];
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct = $_POST['correct_option'];
    $marks = $_POST['marks'];

    $stmt = $pdo->prepare("INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$exam_id, $question, $option_a, $option_b, $option_c, $option_d, $correct, $marks]);
    $success = "Question added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Questions - EaseExam</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .form-container {
      max-width: 700px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .form-container h2 { margin-bottom: 20px; }
    .form-container input, 
    .form-container textarea, 
    .form-container select {
      width: 100%; padding: 12px; margin-bottom: 15px;
      border: 1px solid #ccc; border-radius: 6px;
    }
    .form-container button {
      padding: 12px; width: 100%;
      background: #28a745; color: #fff;
      border: none; border-radius: 6px;
      font-size: 1rem; cursor: pointer;
    }
    .form-container button:hover { background: #218838; }
  </style>
</head>
<body>
  <?php include "../includes/teacher_sidebar.php"; ?>

  <div class="main">
    <div class="form-container">
      <h2>Add Questions</h2>
      <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
      <form method="POST">
        <select name="exam_id" required>
          <option value="">Select Exam</option>
          <?php
          $exams = $pdo->prepare("SELECT * FROM exams WHERE created_by = ?");
          $exams->execute([$_SESSION['user_id']]);
          foreach ($exams as $exam) {
              echo "<option value='{$exam['exam_id']}'>{$exam['title']}</option>";
          }
          ?>
        </select>
        <textarea name="question" placeholder="Enter Question" required></textarea>
        <input type="text" name="option_a" placeholder="Option A" required>
        <input type="text" name="option_b" placeholder="Option B" required>
        <input type="text" name="option_c" placeholder="Option C" required>
        <input type="text" name="option_d" placeholder="Option D" required>
        <select name="correct_option" required>
          <option value="">Select Correct Option</option>
          <option value="a">A</option>
          <option value="b">B</option>
          <option value="c">C</option>
          <option value="d">D</option>
        </select>
        <input type="number" name="marks" placeholder="Marks" required>
        <button type="submit">Add Question</button>
      </form>
    </div>
  </div>
</body>
</html>
