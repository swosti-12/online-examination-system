<?php
include __DIR__ . "/db.php";
// Fetch subjects dynamically
$subjects = $pdo->query("SELECT subject_id, name FROM subjects ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
// Short descriptions per subject (fallback if DB has no description column)
$desc = [
  'english language' => 'Grammar, comprehension, and vocabulary to improve everyday communication.',
  'english literature' => 'Explore prose, poetry, and drama with themes, context, and analysis.',
  'mathematics' => 'Algebra, geometry, arithmetic, and problem‑solving foundations.',
  'science' => 'Key ideas from biology, chemistry, and physics with real‑world links.',
  'social studies' => 'Society, culture, history, and civics to understand communities.',
  'computer studies' => 'Basics of computing, digital literacy, and simple programming logic.',
  'environmental studies' => 'Ecosystems, sustainability, and human impact on the environment.',
  'general knowledge' => 'Facts, current affairs, and trivia across diverse topics.',
  'civics & moral ed.' => 'Rights, duties, values, and ethics for responsible citizenship.',
  'art & craft' => 'Creativity, techniques, and appreciation of visual and applied arts.'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Exams - EaseExam</title>
    <style>
        /* Reset & Basic Styles */
        body, html {
            margin: 0; padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen,
                Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            background: #fff;
            color: #333;
            min-height: 100vh;
        }
        a {
            text-decoration: none;
            color: inherit;
        }
        /* Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 3rem;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            background: white;
            z-index: 1000;
        }
        .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: #2a2af7; /* Blue */
        }
        nav a {
            margin: 0 1rem;
            font-weight: 500;
            color: #666;
            font-size: 1rem;
        }
        nav a.active {
            color: #2a2af7;
            font-weight: 700;
        }
        .btn-login, .btn-register {
            border: 1.5px solid #2a2af7;
            padding: 0.4rem 1.2rem;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-left: 1rem;
            font-size: 0.95rem;
        }
        .btn-login {
            background: transparent;
            color: #2a2af7;
        }
        .btn-login:hover {
            background: #e8e8ff;
        }
        .btn-register {
            background: #2a2af7;
            color: white;
            border: none;
        }
        .btn-register:hover {
            background: #1a1adf;
        }

        /* Main Content */
        main {
            max-width: 900px;
            margin: 3rem auto;
            padding: 0 1rem;
        }

        h1 {
            color: #2a2af7;
            margin-bottom: 2rem;
            font-weight: 800;
            font-size: 2.5rem;
            text-align: center;
        }

        .exam-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        .exam-card {
            border: 1.2px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgb(42 42 247 / 0.1);
            transition: box-shadow 0.3s ease;
            background: #fff;
        }
        .exam-card:hover {
            box-shadow: 0 6px 15px rgb(42 42 247 / 0.2);
        }

        .exam-name {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .exam-desc {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 1rem;
        }
        .btn-start {
            display: inline-block;
            background: #2a2af7;
            color: white;
            padding: 0.5rem 1.4rem;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
        }
        .btn-start:hover {
            background: #1a1adf;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 1rem 0;
            font-size: 0.9rem;
            color: #888;
            border-top: 1px solid #eee;
            margin-top: 4rem;
        }
    </style>
</head>
<body>
<header>
    <a href="#" class="logo">EaseExam</a>
  
    <div>
        <a href="login.php" class="btn-login">Login</a>
        <a href="register.php" class="btn-register">Register</a>
    </div>
</header>

<main>
    <h1>Subjects You Can Take Exams In</h1>
    <div class="exam-list">
      <?php foreach($subjects as $s): ?>
        <div class="exam-card">
          <div class="exam-name"><?php echo htmlspecialchars($s['name']); ?></div>
          <div class="exam-desc"><?php
            $key = strtolower(trim($s['name']));
            echo htmlspecialchars($desc[$key] ?? 'Browse available exams for this subject.');
          ?></div>
          <a href="exam/start_exam.php?subject_id=<?php echo (int)$s['subject_id']; ?>" class="btn-start">Browse Exams</a>
        </div>
      <?php endforeach; ?>
    </div>
</main>

<footer>
    &copy; <?php echo date("Y");?> EaseExam. All rights reserved.
</footer>
</body>
</html>
