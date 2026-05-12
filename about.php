<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | EaseExam Online Examination System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #7209b7;
            --light: #f8f9fa;
            --dark: #2b2d42;
            --text: #2b2d42;
            --background: #f8f9fa;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--background);
            color: var(--text);
            line-height: 1.6;
        }
        
        .container {
            width: 85%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(50deg, var(--primary), var(--secondary));
            color: white;
            padding: 30px 0;
            text-align:left;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            box-shadow: var(--shadow);
        }
        
        .brand {
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
      align-items: center;
      font-size: 1.5rem;
      letter-spacing: -0.5px;
    }
        
        .tagline {
            font-size: 1.2rem;
            font-weight: 300;
            margin-bottom: 20px;
        }
        
        nav {
            background-color: white;
            padding: 15px 0;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: var(--shadow);
        }
        
        nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
        }
        
        nav ul li {
            margin: 0 15px;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--primary);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav ul li a:hover {
            color: var(--accent);
        }
        
        .hero {
            text-align: center;
            padding: 60px 0;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin: 30px 0;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }
        
        .section:hover {
            transform: translateY(-5px);
        }
        
        h2 {
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eaeaea;
            display: flex;
            align-items: center;
        }
        
        h2 i {
            margin-right: 10px;
            color: var(--secondary);
        }
        
        p {
            margin-bottom: 15px;
            font-size: 1.05rem;
        }
        
        .team {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 25px;
        }
        
        .team-member {
            background: white;
            border-radius: 10px;
            padding: 20px;
            width: 250px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }
        
        .team-member:hover {
            transform: translateY(-8px);
        }
        
        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 4px solid #f1f1f1;
        }
        
        .team-member h3 {
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .team-member .role {
            color: var(--secondary);
            font-style: italic;
            margin-bottom: 15px;
        }
        
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        
        .feature {
            flex: 1 1 300px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid var(--secondary);
        }
        
        .feature h3 {
            color: var(--primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .feature h3 i {
            margin-right: 10px;
            color: var(--secondary);
        }
        
        .values {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        
        .value {
            flex: 1 1 250px;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .value i {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 15px;
        }
        
        footer {
            text-align: center;
            padding: 30px 0;
            background: var(--primary);
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            margin-top: 40px;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            color: white;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: #a0c1e4;
        }
        
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }
            
            .team-member {
                width: 100%;
            }
            
            nav ul {
                flex-direction: column;
                align-items: center;
            }
            
            nav ul li {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="#" class="brand">EaseExam</a>
            <div class="tagline">Innovative Online Examination Solutions</div>
        </div>
    </header>

    <div class="container">
        <div class="hero">
            <h1>Learn Anytime, Anywhere!</h1>
            <p>Enhance knowledge and skills through our online platform.</p>
        </div>

        <div class="section">
            <h2><i class="fas fa-info-circle"></i> About EaseExam</h2>
            <p>EaseExam is a comprehensive online examination system designed to provide educational institutions with a modern, secure, and efficient solution for conducting assessments. Our platform addresses the challenges of traditional examination systems by offering a robust digital alternative that maintains academic integrity while providing flexibility.</p>
            <p>Developed with cutting-edge technology, EaseExam streamlines the entire examination process from creation to evaluation, saving educators valuable time while providing students with a seamless testing experience.</p>
        </div>

        <div class="section">
            <h2><i class="fas fa-bullseye"></i> Our Mission</h2>
            <p>Our mission is to revolutionize the examination process by leveraging technology to create a seamless, accessible, and fair assessment environment. We aim to reduce the administrative burden on educators while providing students with a smooth examination experience from any location.</p>
        </div>

        <div class="section">
            <h2><i class="fas fa-star"></i> Key Features</h2>
            <div class="features">
                <div class="feature">
                    <h3><i class="fas fa-shield-alt"></i> QuickQuest</h3>
                    <p>Quick and efficient exam creation with our intuitive snippet system for rapid question deployment.</p>
                </div>
                <div class="feature">
                    <h3><i class="fas fa-camera"></i> Anti-cheating Mechanism</h3>
                    <p>Advanced screen monitoring to prevent cheating and maintain exam integrity during tests.</p>
                </div>
                <div class="feature">
                    <h3><i class="fas fa-check-circle"></i> Automatic</h3>
                    <p>Instant grading for objective questions with detailed analytics and performance reports.</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2><i class="fas fa-users"></i> Our Team</h2>
            <p>EaseExam is developed by a dedicated team of technology enthusiasts passionate about creating innovative educational solutions. Our diverse team brings together expertise in software development, user experience design, and educational methodology.</p>
            <div class="team">
                <div class="team-member">
                    <h3>Shreeba Kunwar</h3>
                    <div class="role">Backend Developer</div>
                    <p>Specializes in backend systems and security architecture</p>
                </div>
                <div class="team-member">
                    <h3>Swostika Upadhyaya</h3>
                    <div class="role">Frontend Developer</div>
                    <p>Creates intuitive and responsive user interfaces</p>
                </div>
                <div class="team-member">
                    <h3>Krisha Tamang</h3>
                    <div class="role">UI/UX & Documentation</div>
                    <p>Designs user experiences and manages project documentation</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2><i class="fas fa-gem"></i> Our Values</h2>
            <div class="values">
                <div class="value">
                    <i class="fas fa-lock"></i>
                    <h3>Security</h3>
                    <p>We prioritize the integrity and confidentiality of all examination data.</p>
                </div>
                <div class="value">
                    <i class="fas fa-lightbulb"></i>
                    <h3>Innovation</h3>
                    <p>We continuously explore new technologies to enhance the assessment experience.</p>
                </div>
                <div class="value">
                    <i class="fas fa-hand-holding-heart"></i>
                    <h3>Accessibility</h3>
                    <p>We believe in creating inclusive solutions that work for all users.</p>
                </div>
                <div class="value">
                    <i class="fas fa-rocket"></i>
                    <h3>Efficiency</h3>
                    <p>We streamline processes to save time for both educators and students.</p>
                </div>
            </div>
        </div>
    </div>
<!-- Floating Buttons -->
<div class="floating-buttons">
    <!-- Move to Top -->
    <button class="float-btn" onclick="scrollToTop()" title="Move to Top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Back to Home -->
    <a href="index.php" title="Back to Home">
        <button class="float-btn">
            <i class="fas fa-home"></i>
        </button>
    </a>
</div>
<script>
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }
</script>


</body>
</html>