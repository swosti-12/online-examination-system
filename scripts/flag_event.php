<?php
session_start();
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['ok' => false, 'error' => 'unauthorized']);
  exit;
}

$userId = (int) $_SESSION['user_id'];
$examId = isset($_POST['exam_id']) ? (int) $_POST['exam_id'] : 0;
$type = isset($_POST['type']) ? trim($_POST['type']) : '';

if ($examId <= 0 || $type === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'bad_request']);
  exit;
}

$stmt = $pdo->prepare("INSERT INTO proctor_events (user_id, exam_id, event_type) VALUES (?, ?, ?)");
$stmt->execute([$userId, $examId, $type]);

echo json_encode(['ok' => true]);
?>


