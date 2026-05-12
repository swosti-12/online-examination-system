<?php
session_start();
include "../db.php";

// Only students allowed
if ($_SESSION['role'] !== 'student') {
    header("Location: ../dashboard.php");
    exit();
}

// Check if exam_id is provided
if (!isset($_GET['exam_id'])) {
    header("Location: start_exam.php");
    exit();
}

$exam_id = $_GET['exam_id'];

// Fetch exam info
$stmt = $pdo->prepare("SELECT * FROM exams WHERE exam_id = ?");
$stmt->execute([$exam_id]);
$exam = $stmt->fetch();

if (!$exam) {
    die("Exam not found.");
}

// Fetch questions
$qstmt = $pdo->prepare("SELECT * FROM questions WHERE exam_id = ?");
$qstmt->execute([$exam_id]);
$questions = $qstmt->fetchAll();
// Shuffle questions order
if ($questions && is_array($questions)) { shuffle($questions); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($exam['title']); ?> - EaseExam</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    :root { --primary:#4f46e5; --primary-dark:#372fba; --bg:#f5f7fb; --ink:#111827; }
    body { background: var(--bg); color: var(--ink); }
    .main { padding: 24px; }
    .exam-container {
      max-width: 900px;
      margin: 30px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      border:1px solid #e5e7eb;
    }
    .exam-container h2 {
      margin-bottom: 10px; color: var(--primary);
    }
    .exam-container .timer {
      font-size: 1.2rem;
      font-weight: bold;
      color: #e63946;
      text-align: right;
      margin-bottom: 20px;
    }
    .question {
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }
    .question h4 {
      margin-bottom: 10px;
      color: #333;
    }
    .options label {
      display: block;
      padding: 8px 12px;
      background: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 6px;
      margin-bottom: 8px;
      cursor: pointer;
      transition: 0.2s;
    }
    .options input {
      margin-right: 10px;
    }
    .options label:hover {
      background: #eef6ff;
      border-color: #007bff;
    }
    .submit-btn {
      display: block;
      margin: 20px auto;
      padding: 12px 24px;
      background: var(--primary);
      border: none;
      color: #fff;
      font-size: 1.1rem;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    .submit-btn:hover {
      background: var(--primary-dark);
    }
  </style>
 <script>
  // Timer (same as before)
  let duration = <?php echo (int)$exam['duration_min']; ?> * 60; // seconds
  function startTimer() {
    let timerDisplay = document.getElementById("timer");
    let timer = setInterval(function () {
      let minutes = Math.floor(duration / 60);
      let seconds = duration % 60;
      timerDisplay.textContent = minutes + "m " + (seconds < 10 ? "0" : "") + seconds + "s";
      duration--;
      if (duration < 0) {
        clearInterval(timer);
        document.getElementById("examForm").submit();
      }
    }, 1000);
  }
  window.onload = startTimer;

  // Prevent copy-paste & right click + log
  const examId = <?php echo (int)$exam_id; ?>;
  function logEvent(type){
    try { navigator.sendBeacon('../scripts/flag_event.php', new URLSearchParams({exam_id: examId, type})); } catch(e) {}
  }
  document.addEventListener("contextmenu", e => { e.preventDefault(); logEvent('contextmenu'); });
  document.addEventListener("copy", e => { e.preventDefault(); logEvent('copy'); });
  document.addEventListener("cut", e => { e.preventDefault(); logEvent('cut'); });
  document.addEventListener("paste", e => { e.preventDefault(); logEvent('paste'); });

  // Detect tab switch or minimize
  let warningCount = 0;
  document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
      warningCount++;
      logEvent('tab_switch');
      alert("⚠️ Warning! Don't switch tabs during the exam. (" + warningCount + ")");
      if (warningCount >= 3) {
        alert("Exam auto-submitted due to multiple violations.");
        document.getElementById("examForm").submit();
      }
    }
  });

  // Force fullscreen
  function openFullscreen() {
    let docEl = document.documentElement;
    if (docEl.requestFullscreen) docEl.requestFullscreen();
    else if (docEl.mozRequestFullScreen) docEl.mozRequestFullScreen();
    else if (docEl.webkitRequestFullscreen) docEl.webkitRequestFullscreen();
    else if (docEl.msRequestFullscreen) docEl.msRequestFullscreen();
  }
  window.onload = function() {
    startTimer();
    openFullscreen();
  }
</script>

</head>
<body>
  <?php include "../includes/student_sidebar.php"; ?>

  <div class="main">
    <div class="exam-container">
      <h2><?php echo htmlspecialchars($exam['title']); ?></h2>
      <p><?php echo htmlspecialchars($exam['description']); ?></p>
      <div class="timer">⏰ Time Left: <span id="timer"></span></div>

      <form id="examForm" method="POST" action="submit_exam.php">
        <input type="hidden" name="exam_id" value="<?php echo $exam['exam_id']; ?>">

        <?php foreach ($questions as $index => $q): ?>
          <div class="question">
            <h4>Q<?php echo $index+1; ?>. <?php echo htmlspecialchars($q['question_text']); ?></h4>
            <div class="options">
              <?php
              // Shuffle options presentation, keep values as original keys
              $optionKeys = ['a','b','c','d'];
              shuffle($optionKeys);
              foreach ($optionKeys as $opt) {
                $field = "option_" . $opt;
                if (!empty($q[$field])) {
                  echo "<label><input type='radio' name='answers[{$q['question_id']}]' value='{$opt}'> ".htmlspecialchars($q[$field])."</label>";
                }
              }
              ?>
            </div>
          </div>
        <?php endforeach; ?>

        <button type="submit" class="submit-btn">Submit Exam</button>
      </form>
    </div>
  </div>
</body>
</html>
