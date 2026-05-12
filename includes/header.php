<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EaseExam</title>
  <link rel="stylesheet" href="/easeexam/style.css">
</head>
<body>
<header>
  <div class="logo">EaseExam</div>
  <nav>
    <ul>
      <li><a href="/easeexam/index.php">Home</a></li>
      <?php if(!isset($_SESSION['user_id'])): ?>
        <li><a href="/easeexam/login.php">Login</a></li>
        <li><a href="/easeexam/register.php">Register</a></li>
      <?php else: ?>
        <li><a href="/easeexam/dashboard.php">Dashboard</a></li>
        <li><a href="/easeexam/logout.php">Logout</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
<main>
