<?php
// Session hardening - MUST be set before session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
  ini_set('session.cookie_secure', 1);
}

session_start();
include "db.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // DEBUG: Proper debug code
    error_log("Email: " . $email);
    error_log("Password: " . $password);
    error_log("Hash: " . ($user['password_hash'] ?? 'NOT_FOUND'));
    
    if ($user) {
        $verify_result = password_verify($password, $user['password_hash']);
        error_log("Password verify: " . ($verify_result ? 'SUCCESS' : 'FAILED'));
        
        if ($verify_result) {
            error_log("Login successful for user: " . $user['email']);
            error_log("User role: " . $user['role']);
            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                error_log("Redirecting to admin dashboard");
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] === 'teacher') {
                error_log("Redirecting to teacher dashboard");
                header("Location: dashboard.php?role=teacher");
            } else {
                error_log("Redirecting to student dashboard");
                header("Location: dashboard.php?role=student");
            }
            exit();
        }
    }
    
    $error = "Invalid email or password!";
    error_log("Login failed for email: " . $email);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - EaseExam</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * { 
      margin: 0; 
      padding: 0; 
      box-sizing: border-box; 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
      background: linear-gradient(135deg, #4f46e5, #22d3ee);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .login-container {
      background: #fff;
      padding: 2.5rem;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 420px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .login-container:hover {
      transform: translateY(-5px);
    }

    .logo {
      margin-bottom: 1.5rem;
      color: #4f46e5;
      font-size: 2.5rem;
    }

    .login-container h2 {
      margin-bottom: 1.5rem;
      color: #333;
      font-weight: 600;
    }

    .tagline {
      color: #666;
      margin-bottom: 2rem;
      font-size: 0.95rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
      text-align: left;
      position: relative;
    }

    label {
      font-size: 0.9rem;
      font-weight: 600;
      color: #444;
      display: block;
      margin-bottom: 0.6rem;
    }

    .input-with-icon {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #777;
    }

    input {
      width: 100%;
      padding: 14px 14px 14px 45px;
      border: 2px solid #e1e1e1;
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s;
    }

    input:focus {
      border-color: #4f46e5;
      outline: none;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }

    .btn {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      background: linear-gradient(to right, #4f46e5, #22d3ee);
      color: #fff;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(79, 70, 229, 0.4);
    }

    .btn:active {
      transform: translateY(0);
    }

    .links {
      margin-top: 1.8rem;
      display: flex;
      flex-direction: column;
      gap: 0.8rem;
    }
    
    .links a {
      color: #4f46e5;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.95rem;
      transition: color 0.2s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
    }
    
    .links a:hover {
      color: #22d3ee;
      text-decoration: underline;
    }

    .error-message {
      background: #fee;
      color: #c33;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      border-left: 4px solid #c33;
      font-weight: 500;
      text-align: center;
    }

    .success-message {
      background: #efe;
      color: #363;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      border-left: 4px solid #363;
      font-weight: 500;
      text-align: center;
    }

    @media (max-width: 480px) {
      .login-container {
        padding: 2rem 1.5rem;
      }
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #777;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="logo">
      <i class="fas fa-graduation-cap"></i>
    </div>
    <h2>Welcome to EaseExam</h2>
    <p class="tagline">Sign in to continue to your account</p>

    <?php if(isset($error)): ?>
      <div class="error-message">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <?php if(isset($_GET['registered']) && $_GET['registered'] == 'true'): ?>
      <div class="success-message">
        Registration successful! Please log in.
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <label for="email">Email Address</label>
        <div class="input-with-icon">
          <i class="fas fa-envelope input-icon"></i>
          <input type="email" id="email" name="email" required placeholder="Enter your email" />
        </div>
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-with-icon">
          <i class="fas fa-lock input-icon"></i>
          <input type="password" id="password" name="password" required placeholder="Enter your password" />
          <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
        </div>
      </div>
      
      <button type="submit" class="btn">
        <i class="fas fa-sign-in-alt"></i> Login
      </button>
    </form>

    <div class="links">
      <a href="register.php">
        <i class="fas fa-user-plus"></i> Create an Account
      </a>
      <a href="forgot_password.php">
        <i class="fas fa-key"></i> Forgot Password?
      </a>
    </div>
  </div>

  <script>
    // Password visibility toggle
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordInput = document.getElementById('password');
    
    passwordToggle.addEventListener('click', function() {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.classList.remove('fa-eye');
        passwordToggle.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        passwordToggle.classList.remove('fa-eye-slash');
        passwordToggle.classList.add('fa-eye');
      }
    });
  </script>
</body>
</html>