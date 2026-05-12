<?php
session_start();
include "../db.php";

// Only students allowed
if ($_SESSION['role'] !== 'student') {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: start_exam.php");
    exit();
}

$exam_id = $_POST['exam_id'];
$user_id = $_SESSION['user_id'];
$answers = $_POST['answers'] ?? [];

// Fetch correct answers
$stmt = $pdo->prepare("SELECT question_id, correct_option, marks FROM questions WHERE exam_id = ?");
$stmt->execute([$exam_id]);
$questions = $stmt->fetchAll();

$total_score = 0;
$max_score = 0;

foreach ($questions as $q) {
    $max_score += $q['marks'];
    $qid = $q['question_id'];

    if (isset($answers[$qid]) && $answers[$qid] == $q['correct_option']) {
        $total_score += $q['marks'];
    }
}

// Calculate percentage
$percentage = $max_score > 0 ? round(($total_score / $max_score) * 100, 2) : 0;

// Save result in DB
$stmt = $pdo->prepare("INSERT INTO results (exam_id, user_id, score, submitted_at) VALUES (?, ?, ?, NOW())");
$stmt->execute([$exam_id, $user_id, $percentage]);

// Redirect to results page
header("Location: my_results.php?exam_id=" . $exam_id);
exit();
?>
