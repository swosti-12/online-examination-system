<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EaseExam - Online Examination System</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <!-- Tailwind CSS for utility classes -->
    <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html { scroll-behavior: smooth; }

    /* Hero Button */
    .btn-get-started {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 25px;
      font-size: 1.1rem;
      font-weight: bold;
      background: #4f46e5;
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.3s;
    }
    .btn-get-started:hover {
      background: #372fba;
    }

    /* Center hero content nicely */
    .hero-content {
      text-align: center;
    }
    #scrollTopBtn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 100;
  font-size: 20px;
  border: none;
  outline: none;
  background: #4f46e5;
  color: white;
  cursor: pointer;
  padding: 12px;
  border-radius: 50%;
  transition: 0.3s;
}
#scrollTopBtn:hover {
  background: #372fba;
}
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
      }
      footer {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
        /* Dark gradient for depth */
        color: #e8e8e8;
        /* Soft off-white for readability */
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
        /* Subtle shadow for elevation */
      }
      footer a {
        transition: color 0.3s ease, transform 0.2s ease;
      }
      footer a:hover {
        color: #63b3ed;
        /* Blue accent on hover */
        transform: translateY(-2px);
        /* Lift effect for interactivity */
      }
      .section-divider {
        border-left: 1px solid #4a5568;
        /* Thin divider between sections */
      }
         .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }.social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            transform: translateY(-3px);
        }
        
      .copyright {
        background: rgba(0, 0, 0, 0.2);
        /* Subtle overlay for copyright area */
        padding: 10px 0;
      }
      /* Responsive adjustments */
      @media (max-width: 768px) {
        .footer-grid {
          grid-template-columns: repeat(1, 1fr);
        }
        .section-divider {
          border-left: none;
          border-top: 1px solid #4a5568;
          margin-top: 15px;
          padding-top: 15px;
        }
      }
  </style>
</head>
<body>

 <header>
<a href="index.php" class="brand">
    <img src="images/graduation-cap.png" alt="EaseExam Logo" style="height:40px; vertical-align:middle; margin-right:8px;">
    EaseExam
  </a>
    <div class="nav-container">
      <!-- nav links to other pages-->
      <nav class="navlinks" aria-label="Primary">
        <a href="#features">Features</a>
        <a href="contact.php">Contact</a>
        <a href="exam.php" data-route>Exams</a>
        <a href="about.php" data-route>About</a>
      </nav>

      <div class="right">
        <a class="btn small ghost" href="login.php?role=student" data-route data-role="student">Login</a>
        <a class="btn small primary" href="register.php" data-route>Register</a>
      </div>
    </div>
  </header>

  <section class="hero" id="hero-section">
    <div class="overlay"></div>
    <div class="hero-content">
      <h1 id="hero-title">Your Future, One Exam Away!</h1>
      <p id="hero-subtext">Enhance knowledge and skills through our online platform.</p>
      <!-- ✅ New Get Started Button -->
      <a href="register.php" class="btn-get-started">  Get Started Now</a>
    </div>
  </section>
<!-- feature section -->
  <section class="features" id="features"> <!-- ✅ added id for smooth scroll -->
    <div class="feature-card">
      <img src="images/combo-chart.png" alt="Track Icon">
      <h3>Track Performance</h3>
      <p>Monitor your progress and results</p>
    </div>
    <div class="feature-card">
      <img src="images/security-checked.png" alt="Secure Icon">
      <h3>Secure Exams</h3>
      <p>Experience a safe cheat-free environment</p>
    </div>
    <div class="feature-card">
      <img src="images/open-book.png" alt="Book Icon">
      <h3>For Students, For Teachers</h3>
      <p>Tools and resources for both learners and educators</p>
    </div>
      
    <div class="feature-card">
      <img src="images/timer.png" alt="Timer Icon">
      <h3>Time Management</h3>
      <p>Built-in timers help students complete exams on time.</p>
    </div>
    
    <div class="feature-card">
      <img src="images/mobile.png" alt="Mobile Icon">
      <h3>Mobile Friendly</h3>
      <p>Access your exams anytime, anywhere from any device.</p>
    </div>
    
    <div class="feature-card">
      <img src="images/community.png" alt="Community Icon">
      <h3>Community Support</h3>
      <p>Collaborate with teachers and peers for better learning.</p>
    </div>

  </section>

 <!-- Scroll to Top Button -->
<button id="scrollTopBtn" title="Go to top">Go to top</button>
<script>
  const scrollBtn = document.getElementById("scrollTopBtn");

  window.onscroll = function() {
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
      scrollBtn.style.display = "block";
    } else {
      scrollBtn.style.display = "none";
    }
  };

  scrollBtn.onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };
</script>


<!--footer section  -->
  <footer class="text-white py-12">
    <div class="container mx-auto px-6">
      <div class="footer-grid grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- About Us Link Section -->
        <div>
          <h3 class="text-xl font-semibold mb-4">About Us</h3>
          <p class="text-sm text-gray-300 leading-relaxed mb-4">
            Learn more about our mission, vision, and team.
          </p>
          <a
            href="about.php"
            class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded text-white font-medium transition"
            >Visit About Us Page</a
          >
        </div>

        <!-- Quick Links Section -->
        <div class="section-divider pl-0 md:pl-8">
          <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li>
              <a href="#" class="text-gray-300 hover:text-blue-300"
                ><i class="fas fa-home mr-2"></i>Home</a
              >
            </li>
            <li>
              <a href="exam.php" class="text-gray-300 hover:text-blue-300"
                ><i class="fas fa-file-alt mr-2"></i>Exams</a
              >
            </li>
            <li>
              <a href="exam/view_results.php" class="text-gray-300 hover:text-blue-300"
                ><i class="fas fa-chart-bar mr-2"></i>Results</a
              >
            </li>
            <li>
              <a href="faq.php" class="text-gray-300 hover:text-blue-300"
                ><i class="fas fa-question-circle mr-2"></i>FAQs</a
              >
            </li>
          </ul>
        </div>

        <!-- Legal & Support Section -->
        <div class="section-divider pl-0 md:pl-8">
          <h3 class="text-xl font-semibold mb-4">Legal & Support</h3>
          <ul class="space-y-2">
            <li>
              <a href="privacy.php" class="text-gray-300 hover:text-blue-300"
                ><i class="fas fa-shield-alt mr-2"></i>Privacy Policy</a
              >
            </li>
            <li>
              <a href="terms.php" class="text-gray-300 hover:text-blue-300"
                ><i class="fas fa-file-contract mr-2"></i>Terms of Service</a
              >
            </li>
            <li>
              <a href="contact.php" class="text-gray-300 hover:text-blue-300"
                ><i class="fas fa-envelope mr-2"></i>Contact Support</a
              >
            </li>
            <li>
              <a href="troubleshoot.php" class="text-gray-300 hover:text-blue-300"
                ><i class="fas fa-tools mr-2"></i>Troubleshooting</a
              >
            </li>
          </ul>
        </div>

        <!-- Social & Connect Section -->
        <div class="section-divider pl-0 md:pl-8">
          <h3 class="text-xl font-semibold mb-4">Connect with Us</h3>
          <p class="text-sm text-gray-300 mb-4">
            Follow us for updates on new features and exam tips.
          </p>
            <div class="social-icons">
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.github.com/"><i class="fab fa-github"></i></a>

                        
                    </div>
          <div>
            <a
              href="mailto:support@exam-system.com"
              class="text-gray-300 hover:text-blue-300 text-sm"
              ><i class="fas fa-mail-bulk mr-2"></i>support@exam-system.com</a
            >
          </div>
        </div>
      </div>
    </div>

    <!-- Copyright -->
    <div class="copyright mt-8">
      <div
        class="container mx-auto px-6 text-center text-sm text-gray-400"
      >
        <p>
          &copy; 2025 Online Examination System. All Rights Reserved. Powered by
          innovative technology for secure assessments.
        </p>
      </div>
    </div>
  </footer>
  <script>
    const titles = [
      "Your Future, One Exam Away!",
      "Achieve Your Dreams!",
      "Learn Anytime, Anywhere!",
      "Grow Your Skills Today!"
    ];
   const backgrounds = [
      "images/desk.avif",
      "images/read.avif",
      "images/maths.avif",
      "images/123.avif",
    ];
    let index = 0;
    const heroTitle = document.getElementById("hero-title");
    const heroSection = document.getElementById("hero-section");

    function updateHero() {
      heroTitle.style.opacity = 0;
      setTimeout(() => {
        heroTitle.textContent = titles[index];
        heroSection.style.backgroundImage = `url('${backgrounds[index]}')`;
        heroTitle.style.opacity = 1;
      }, 500);
      index = (index + 1) % titles.length;
    }
    heroSection.style.backgroundImage = `url('${backgrounds[0]}')`;
    setInterval(updateHero, 4000);
  </script>
 
</body>
</html>
