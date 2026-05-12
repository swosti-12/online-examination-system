<?php
include "db.php";

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($first_name === "" || $last_name === "" || $email === "" || $message === "") {
        $error = "⚠ Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "⚠ Invalid email format.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (first_name, last_name, email, message) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$first_name, $last_name, $email, $message])) {
            $success = "✅ Message sent successfully! We’ll get back to you soon.";
        } else {
            $error = "❌ Something went wrong. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - EaseExam</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: #fdfbf9;
      margin: 0; padding: 0;
    }
    .container {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      padding: 40px;
    }
    .left {
      flex: 1;
      padding: 20px;
    }
    .left h1 {
      color: #378198ff;
      margin-bottom: 10px;
    }
    .left p {
      margin-bottom: 15px;
      color: #333333ff;
    }
    .left a {
      color: #7b341e;
      text-decoration: none;
    }
    .right {
      flex: 1;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-group {
      margin-bottom: 1rem;
    }
    label {
      display: block;
      font-size: 0.9rem;
      margin-bottom: 5px;
      color: #447c9aff;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }
    textarea {
      resize: vertical;
      height: 120px;
    }
    button {
      padding: 12px;
      background: #4283a6ff;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      width: 100%;
    }
    button:hover { background: #379387ff; }
    .message {
      margin-bottom: 15px;
      font-weight: bold;
    }
    .success { color: green; }
    .error { color: red; }
    .home-btn {
      display:inline-block;
      margin-top:15px;
      padding:10px 20px;
      background:#4f46e5;
      color:white;
      border-radius:6px;
      text-decoration:none;
      font-weight:bold;
    }
    .home-btn:hover {
      background:#372fba;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left">
      <h1>Get in Touch</h1>
      <p>We’d like to hear from you!</p>
      <p>If you have any inquiries or just want to say hi, please use the contact form!</p>
      <p>Email: <a href="mailto:easeexam@gmail.com">easeexam@gmail.com</a></p>
      <p>
        <i class="fab fa-instagram"></i>
        <i class="fab fa-facebook"></i>
        <i class="fab fa-twitter"></i>
        <i class="fab fa-linkedin"></i>
      </p>
    </div>
    <div class="right">
      <?php if($success): ?>
        <p class="message success"><?= $success ?></p>
        <a href="index.php" class="home-btn">🏠 Go Back to Home</a>
      
      <?php else: ?>
        <?php if($error): ?>
          <p class="message error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>
          </div>
          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>
          </div>
          <div class="form-group">
            <label for="email">Email*</label>
            <input type="email" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" required></textarea>
          </div>
          <button type="submit">Send</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
