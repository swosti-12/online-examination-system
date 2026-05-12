<?php
// faq.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ | Online Examination System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #4361ee;
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .header p {
            color: #555;
            font-size: 1rem;
        }

        .faq-item {
            background: #fff;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            padding: 20px;
        }

        .faq-item h3 {
            color: #3a0ca3;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .faq-item p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #444;
        }

        .back-home {
            text-align: center;
            margin-top: 30px;
        }

        .back-home a {
            text-decoration: none;
            background-color: #4361ee;
            color: #fff;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .back-home a:hover {
            background-color: #3a0ca3;
        }

        @media (max-width: 600px) {
            .header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Frequently Asked Questions (FAQ)</h1>
        <p>Online Examination System</p>
    </div>

    <div class="faq-item">
        <h3>1. What is an Online Examination System?</h3>
        <p>
            An Online Examination System is a digital platform that allows students to take exams online
            and enables teachers to create, manage, and evaluate examinations efficiently.
        </p>
    </div>

    <div class="faq-item">
        <h3>2. How do students log in to the system?</h3>
        <p>
            Students can log in using their registered username and password provided by the institution.
            After login, they can view available exams and their results.
        </p>
    </div>

    <div class="faq-item">
        <h3>3. Is the online exam secure?</h3>
        <p>
            Yes, the system uses authentication, time limits, and monitoring features to ensure fairness
            and prevent unauthorized access during examinations.
        </p>
    </div>

    <div class="faq-item">
        <h3>4. Can exams be automatically graded?</h3>
        <p>
            Objective-type questions such as multiple-choice questions are graded automatically,
            providing instant results to students.
        </p>
    </div>

    <div class="faq-item">
        <h3>5. What happens if internet connection is lost during the exam?</h3>
        <p>
            The system can save answers automatically at regular intervals.
            Students can continue the exam once the connection is restored, depending on exam rules.
        </p>
    </div>

    <div class="faq-item">
        <h3>6. Can teachers view student performance?</h3>
        <p>
            Yes, teachers can access detailed reports and analytics to evaluate student performance
            and improve learning outcomes.
        </p>
    </div>

    <div class="back-home">
        <a href="index.php">Back to Home</a>
    </div>
</div>

</body>
</html>
