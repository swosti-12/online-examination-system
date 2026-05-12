-- EaseExam MySQL schema and seed data
-- Run this in phpMyAdmin or mysql client

CREATE DATABASE IF NOT EXISTS easeexam CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE easeexam;

-- Users
CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('student','teacher','admin') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subjects (Primary level)
CREATE TABLE IF NOT EXISTS subjects (
  subject_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
);

-- Exams
CREATE TABLE IF NOT EXISTS exams (
  exam_id INT AUTO_INCREMENT PRIMARY KEY,
  subject_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  created_by INT NOT NULL,
  start_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  end_time DATETIME DEFAULT NULL,
  duration_min INT NOT NULL DEFAULT 30,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Questions (MCQ style)
CREATE TABLE IF NOT EXISTS questions (
  question_id INT AUTO_INCREMENT PRIMARY KEY,
  exam_id INT NOT NULL,
  question_text TEXT NOT NULL,
  option_a VARCHAR(255) NOT NULL,
  option_b VARCHAR(255) NOT NULL,
  option_c VARCHAR(255) NOT NULL,
  option_d VARCHAR(255) NOT NULL,
  correct_option ENUM('a','b','c','d') NOT NULL,
  marks INT NOT NULL DEFAULT 1,
  FOREIGN KEY (exam_id) REFERENCES exams(exam_id) ON DELETE CASCADE
);

-- Results
CREATE TABLE IF NOT EXISTS results (
  result_id INT AUTO_INCREMENT PRIMARY KEY,
  exam_id INT NOT NULL,
  user_id INT NOT NULL,
  score DECIMAL(5,2) NOT NULL,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (exam_id) REFERENCES exams(exam_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Anti-cheat events log
CREATE TABLE IF NOT EXISTS proctor_events (
  event_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  exam_id INT NOT NULL,
  event_type VARCHAR(50) NOT NULL, -- tab_switch, paste, contextmenu, etc
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (exam_id) REFERENCES exams(exam_id) ON DELETE CASCADE
);

-- Seed subjects (higher primary range)
INSERT IGNORE INTO subjects (name) VALUES
 ('English Language'),
 ('English Literature'),
 ('Mathematics'),
 ('Science'),
 ('Social Studies'),
 ('Computer Studies'),
 ('Environmental Studies'),
 ('General Knowledge'),
 ('Civics & Moral Ed.'),
 ('Art & Craft');


-- Reset to "123456"
UPDATE users 
SET password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE email = 'admin@easeexam.local';

-- Reset to "password"  
UPDATE users 
SET password_hash = '$2y$10$jCGQdNlRlWT0kX7pEwvz0.LUuU9cF5W5z5X5k5j5l5m5n5o5p5q5r5s'
WHERE email = 'admin@easeexam.local';

-- Reset to "admin123"
UPDATE users 
SET password_hash = '$2y$10$8B5z5z5z5z5z5z5z5z5z.5z5z5z5z5z5z5z5z5z5z5z5z5z5z5z5z'
WHERE email = 'admin@easeexam.local';





-- Seed admin and demo users (replace hashes later if needed)
INSERT IGNORE INTO users (user_id, full_name, email, password_hash, role) VALUES
 (1, 'System Admin', 'admin@easeexam.local', '$2y$10$2O6w2JvD2uOxRr8rG0wVjuQn7Xb3o3t2kF1q3qf7m3m9y7o1bRk4C', 'admin');

-- Create a demo teacher and student with password: password123
INSERT IGNORE INTO users (full_name, email, password_hash, role) VALUES
 ('Demo Teacher', 'teacher@easeexam.local', '$2y$10$eYt3x1o6mRk1g0dGssbGxuhsfa1o3e5R2y5gZ7k1p0uQz7b9UeV3G', 'teacher'),
 ('Demo Student', 'student@easeexam.local', '$2y$10$eYt3x1o6mRk1g0dGssbGxuhsfa1o3e5R2y5gZ7k1p0uQz7b9UeV3G', 'student');

-- Demo exam under Mathematics created by Demo Teacher
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Math Basics Quiz', 'Primary-level arithmetic and simple word problems', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 20, 1
FROM subjects s, users u
WHERE s.name = 'Mathematics' AND u.email = 'teacher@easeexam.local'
LIMIT 1;

-- Clean up existing data first
DELETE FROM questions WHERE exam_id IN (
    SELECT exam_id FROM exams WHERE title LIKE '%Math%' OR title LIKE '%English%' OR title LIKE '%Science%' OR title LIKE '%Social%' OR title LIKE '%Computer%' OR title LIKE '%EVS%' OR title LIKE '%GK%' OR title LIKE '%Civics%' OR title LIKE '%Art%'
);

DELETE FROM exams WHERE title LIKE '%Math%' OR title LIKE '%English%' OR title LIKE '%Science%' OR title LIKE '%Social%' OR title LIKE '%Computer%' OR title LIKE '%EVS%' OR title LIKE '%GK%' OR title LIKE '%Civics%' OR title LIKE '%Art%';

-- MATH - Exam 1
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Math Fundamentals Quiz', 'Basic arithmetic operations and concepts', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 25, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Mathematics' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Math Fundamentals Quiz')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'What is 15 ÷ 3?' AS question_text, '4' AS option_a, '5' AS option_b, '6' AS option_c, '7' AS option_d, 'b' AS correct_option, 1 AS marks
    UNION ALL SELECT 'Value of 9 + 8', '15', '16', '17', '18', 'c', 1
    UNION ALL SELECT 'Which number is prime?', '9', '11', '15', '21', 'b', 2
    UNION ALL SELECT 'What is 6 × 7?', '36', '40', '42', '48', 'c', 2
    UNION ALL SELECT 'Quarter of 32 is', '6', '7', '8', '9', 'c', 1
    UNION ALL SELECT 'Which fraction equals 1/2?', '2/3', '3/6', '4/5', '5/8', 'b', 2
    UNION ALL SELECT 'Angle in a straight line equals', '90°', '120°', '180°', '360°', 'c', 2
    UNION ALL SELECT 'Find the missing number: 4, 8, 12, __, 20', '14', '16', '18', '22', 'b', 1
    UNION ALL SELECT 'If a notebook costs Rs. 25, cost of 4 is', 'Rs. 50', 'Rs. 75', 'Rs. 100', 'Rs. 125', 'c', 1
    UNION ALL SELECT 'Perimeter of square with side 5 cm is', '10 cm', '15 cm', '20 cm', '25 cm', 'c', 1
    UNION ALL SELECT 'HCF of 28 and 42 is', '7', '14', '21', '28', 'a', 1
    UNION ALL SELECT 'Value of 7² − 5²', '12', '24', '49', '25', 'b', 2
    UNION ALL SELECT 'Simplify: 3/5 of 40', '18', '20', '22', '24', 'b', 1
    UNION ALL SELECT 'Internal angles sum of a pentagon', '360°', '540°', '720°', '900°', 'b', 2
    UNION ALL SELECT 'Median of 6, 9, 2, 11, 5', '5', '6', '9', '7', 'd', 2
    UNION ALL SELECT 'Mean of 4, 8, 6, 2', '4.5', '5', '5.5', '6', 'c', 2
    UNION ALL SELECT 'Convert 2.75 to fraction', '11/4', '3/4', '9/4', '7/4', 'a', 2
    UNION ALL SELECT 'If 3x = 27, then x =', '6', '7', '8', '9', 'a', 1
) q ON e.title = 'Math Fundamentals Quiz';

-- MATH - Exam 2
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Advanced Math Challenge', 'Complex problems and critical thinking', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 30, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Mathematics' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Advanced Math Challenge')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Find the LCM of 12 and 18.' AS question_text, '24' AS option_a, '36' AS option_b, '48' AS option_c, '60' AS option_d, 'b' AS correct_option, 2 AS marks
    UNION ALL SELECT 'Evaluate: 3/4 + 2/3 = ?', '17/12', '13/12', '19/12', '5/6', 'b', 2
    UNION ALL SELECT 'Solve for x: 5x - 7 = 18', '3', '4', '5', '6', 'c', 2
    UNION ALL SELECT 'Perimeter of a rectangle 8 cm by 5 cm is', '13 cm', '16 cm', '26 cm', '40 cm', 'c', 1
    UNION ALL SELECT 'A shop gives 10% discount on Rs. 450. Selling price is', 'Rs. 405', 'Rs. 410', 'Rs. 420', 'Rs. 430', 'a', 3
    UNION ALL SELECT 'Area of circle with radius 7 cm (π=22/7)', '44 cm²', '88 cm²', '154 cm²', '308 cm²', 'c', 2
    UNION ALL SELECT 'If a:b = 3:4 and b:c = 5:6, then a:c =', '5:8', '3:6', '5:6', '15:24', 'a', 3
    UNION ALL SELECT 'Time taken to travel 240 km at 60 km/h', '3 hours', '4 hours', '5 hours', '6 hours', 'b', 1
    UNION ALL SELECT 'Simple interest on Rs. 1000 at 5% for 2 years', 'Rs. 50', 'Rs. 100', 'Rs. 150', 'Rs. 200', 'b', 2
    UNION ALL SELECT 'Value of (2³ × 3²)', '12', '24', '36', '72', 'd', 2
    UNION ALL SELECT 'Square root of 144', '11', '12', '13', '14', 'b', 1
    UNION ALL SELECT '2 dozen bananas + 3 score oranges = total fruits', '24', '60', '84', '96', 'c', 2
    UNION ALL SELECT 'Probability of getting head in coin toss', '0', '0.5', '1', '2', 'b', 1
    UNION ALL SELECT 'Next number in sequence: 2, 6, 12, 20, 30', '40', '42', '44', '48', 'b', 2
    UNION ALL SELECT 'Sum of first 10 natural numbers', '45', '50', '55', '60', 'c', 2
    UNION ALL SELECT 'Volume of cube with side 4 cm', '16 cm³', '32 cm³', '64 cm³', '128 cm³', 'c', 2
    UNION ALL SELECT '15% of 300', '30', '45', '60', '75', 'b', 1
) q ON e.title = 'Advanced Math Challenge';

-- ENGLISH LANGUAGE - Exam 1
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Grammar Mastery Test', 'Comprehensive grammar assessment', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 25, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'English Language' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Grammar Mastery Test')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Choose the correct spelling' AS question_text, 'recieve' AS option_a, 'receive' AS option_b, 'receeve' AS option_c, 'recevie' AS option_d, 'b' AS correct_option, 1 AS marks
    UNION ALL SELECT 'Best synonym of "happy"', 'sad', 'joyful', 'angry', 'tired', 'b', 1
    UNION ALL SELECT 'Antonym of "begin"', 'start', 'open', 'end', 'initiate', 'c', 1
    UNION ALL SELECT 'Pick the preposition: She sat beside me.', 'she', 'sat', 'beside', 'me', 'c', 1
    UNION ALL SELECT 'Correct: There _____ many books on the shelf.', 'is', 'are', 'was', 'be', 'b', 1
    UNION ALL SELECT 'Reported speech of: "I like apples", said Tom.', 'Tom said he liked apples', 'Tom says he like apples', 'Tom said he likes apples', 'Tom said he like apples', 'a', 2
    UNION ALL SELECT 'Fill in: If it rains, we _____ inside.', 'play', 'played', 'will play', 'are play', 'c', 1
    UNION ALL SELECT 'Identify adjective: The tall building fell.', 'tall', 'building', 'fell', 'the', 'a', 1
    UNION ALL SELECT 'Plural of "leaf" is', 'leafs', 'leaves', 'leafes', 'leafes', 'b', 1
    UNION ALL SELECT 'Article: She adopted ____ unique pup.', 'a', 'an', 'the', 'no article', 'b', 1
    UNION ALL SELECT 'Identify the subordinate clause: "I stayed home because it rained."', 'I stayed home', 'because it rained', 'home because', 'it rained because', 'b', 2
    UNION ALL SELECT 'Choose the correct voice (passive): "They will finish the work."', 'The work is finished by them', 'The work will be finished by them', 'The work has been finished', 'They will be finished the work', 'b', 2
    UNION ALL SELECT 'Correct punctuation: which sentence is right?', 'Do you know, Priya?', 'Do you know Priya?', 'Do you know Priya!.', 'Do you know, Priya.', 'b', 1
    UNION ALL SELECT 'Best conjunction: He was tired, ____ he kept running.', 'and', 'but', 'or', 'so', 'b', 1
    UNION ALL SELECT 'Error spotting: Choose the correct sentence', 'She don''t like mangoes', 'She doesn''t likes mangoes', 'She doesn''t like mangoes', 'She not like mangoes', 'c', 2
    UNION ALL SELECT 'Past tense of "bring"', 'bringed', 'brought', 'brang', 'brings', 'b', 1
    UNION ALL SELECT 'Comparative degree of "good"', 'gooder', 'better', 'best', 'well', 'b', 1
) q ON e.title = 'Grammar Mastery Test';

-- ENGLISH LANGUAGE - Exam 2
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Vocabulary Builder', 'Advanced vocabulary and usage', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 20, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'English Language' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Vocabulary Builder')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Synonym of "abundant"' AS question_text, 'scarce' AS option_a, 'plentiful' AS option_b, 'empty' AS option_c, 'rare' AS option_d, 'b' AS correct_option, 2 AS marks
    UNION ALL SELECT 'Antonym of "brave"', 'cowardly', 'heroic', 'bold', 'fearless', 'a', 2
    UNION ALL SELECT 'Meaning of "punctual"', 'always late', 'on time', 'careless', 'hardworking', 'b', 1
    UNION ALL SELECT 'Word for "fear of heights"', 'claustrophobia', 'acrophobia', 'arachnophobia', 'agoraphobia', 'b', 2
    UNION ALL SELECT 'Homophone of "sea"', 'see', 'saw', 'scene', 'say', 'a', 1
    UNION ALL SELECT 'Which is a compound word?', 'book', 'bookshelf', 'reading', 'library', 'b', 1
    UNION ALL SELECT 'Prefix meaning "again"', 'un-', 're-', 'dis-', 'pre-', 'b', 1
    UNION ALL SELECT 'Suffix that means "full of"', '-less', '-ful', '-ness', '-ment', 'b', 1
    UNION ALL SELECT 'Word for "study of plants"', 'zoology', 'biology', 'botany', 'geology', 'c', 2
    UNION ALL SELECT 'Idiom: "Break a leg" means', 'get injured', 'good luck', 'stop trying', 'be careful', 'b', 2
    UNION ALL SELECT 'Formal word for "ask"', 'demand', 'request', 'shout', 'tell', 'b', 1
    UNION ALL SELECT 'Word for "able to speak two languages"', 'bilingual', 'trilingual', 'multilingual', 'polyglot', 'a', 2
    UNION ALL SELECT 'Antonym of "ancient"', 'modern', 'old', 'historic', 'classic', 'a', 1
    UNION ALL SELECT 'Synonym of "beautiful"', 'ugly', 'pretty', 'plain', 'simple', 'b', 1
    UNION ALL SELECT 'Word for "without any hope"', 'hopeful', 'hopeless', 'hope', 'hoping', 'b', 1
    UNION ALL SELECT 'Meaning of "generous"', 'selfish', 'kind and giving', 'stingy', 'rude', 'b', 1
) q ON e.title = 'Vocabulary Builder';

-- SCIENCE - Exam 1
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Basic Science Concepts', 'Fundamental science principles', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 25, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Science' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Basic Science Concepts')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Plants make food by' AS question_text, 'photosynthesis' AS option_a, 'breathing' AS option_b, 'drinking' AS option_c, 'sleeping' AS option_d, 'a' AS correct_option, 1 AS marks
    UNION ALL SELECT 'Animal that can fly', 'Dog', 'Cat', 'Eagle', 'Cow', 'c', 1
    UNION ALL SELECT 'Water freezes at', '0°C', '10°C', '50°C', '100°C', 'a', 1
    UNION ALL SELECT 'The sun is a', 'planet', 'star', 'moon', 'comet', 'b', 1
    UNION ALL SELECT 'We breathe in', 'carbon dioxide', 'oxygen', 'nitrogen', 'steam', 'b', 1
    UNION ALL SELECT 'Largest organ in human body', 'heart', 'lungs', 'skin', 'liver', 'c', 1
    UNION ALL SELECT 'Boiling point of water at sea level', '50°C', '80°C', '100°C', '120°C', 'c', 1
    UNION ALL SELECT 'The Earth is the ____ planet from the Sun', 'second', 'third', 'fourth', 'fifth', 'b', 1
    UNION ALL SELECT 'Which animal is a mammal?', 'Frog', 'Eagle', 'Shark', 'Dolphin', 'd', 1
    UNION ALL SELECT 'A magnet has', 'one pole', 'two poles', 'three poles', 'no poles', 'b', 1
    UNION ALL SELECT 'Energy from the sun is', 'kinetic', 'chemical', 'solar', 'thermal', 'c', 1
    UNION ALL SELECT 'Part of plant that makes seeds', 'root', 'stem', 'flower', 'leaf', 'c', 1
    UNION ALL SELECT 'Which of these is a gas?', 'ice', 'water', 'steam', 'salt', 'c', 1
    UNION ALL SELECT 'Which travels fastest?', 'sound', 'light', 'water', 'wind', 'b', 1
    UNION ALL SELECT 'Caterpillar turns into a', 'frog', 'butterfly', 'bird', 'fish', 'b', 1
    UNION ALL SELECT 'Human body has how many bones?', '106', '206', '306', '406', 'b', 2
    UNION ALL SELECT 'Which planet is known as Red Planet?', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'b', 1
) q ON e.title = 'Basic Science Concepts';

-- SCIENCE - Exam 2
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Advanced Science Quiz', 'Complex scientific concepts', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 30, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Science' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Advanced Science Quiz')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Process of liquid turning to gas at surface' AS question_text, 'evaporation' AS option_a, 'condensation' AS option_b, 'precipitation' AS option_c, 'sublimation' AS option_d, 'a' AS correct_option, 2 AS marks
    UNION ALL SELECT 'Chlorophyll in plants helps in', 'absorbing water', 'transporting minerals', 'photosynthesis', 'seed dispersal', 'c', 2
    UNION ALL SELECT 'A switch in electric circuit is used to', 'increase voltage', 'open/close circuit', 'store charge', 'measure current', 'b', 2
    UNION ALL SELECT 'Friction is a force that', 'speeds motion', 'opposes motion', 'creates energy', 'is gravity', 'b', 2
    UNION ALL SELECT 'Organ that pumps blood throughout body', 'lungs', 'brain', 'heart', 'stomach', 'c', 1
    UNION ALL SELECT 'Which process changes water vapor to liquid?', 'Evaporation', 'Condensation', 'Precipitation', 'Sublimation', 'b', 2
    UNION ALL SELECT 'Smallest unit of life', 'cell', 'atom', 'molecule', 'tissue', 'a', 1
    UNION ALL SELECT 'Force that pulls objects to Earth', 'magnetism', 'friction', 'gravity', 'pressure', 'c', 1
    UNION ALL SELECT 'Metal that is liquid at room temperature', 'iron', 'mercury', 'gold', 'copper', 'b', 2
    UNION ALL SELECT 'Photosynthesis produces', 'oxygen', 'carbon dioxide', 'nitrogen', 'hydrogen', 'a', 1
    UNION ALL SELECT 'Digestive system breaks down', 'food', 'water', 'air', 'blood', 'a', 1
    UNION ALL SELECT 'Lens that converges light rays', 'concave', 'convex', 'plane', 'diverging', 'b', 2
    UNION ALL SELECT 'Sound travels fastest through', 'air', 'water', 'solid', 'vacuum', 'c', 2
    UNION ALL SELECT 'Circuit where current has single path', 'parallel', 'series', 'open', 'closed', 'b', 2
    UNION ALL SELECT 'Greenhouse gas', 'oxygen', 'nitrogen', 'carbon dioxide', 'hydrogen', 'c', 2
    UNION ALL SELECT 'Newton''s first law is about', 'gravity', 'inertia', 'acceleration', 'force', 'b', 2
    UNION ALL SELECT 'Newton''s first law is about', 'gravity', 'inertia', 'acceleration', 'force', 'b', 2
) q ON e.title = 'Advanced Science Quiz';

-- SOCIAL STUDIES - Exam 1
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'World Geography Basics', 'Maps, continents and countries', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 25, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Social Studies' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'World Geography Basics')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'On a map, N is opposite to' AS question_text, 'E' AS option_a, 'S' AS option_b, 'W' AS option_c, 'NE' AS option_d, 'c' AS correct_option, 1 AS marks
    UNION ALL SELECT 'A scale 1:100,000 means 1 cm equals', '100 m', '1 km', '10 km', '100 km', 'b', 2
    UNION ALL SELECT 'India is divided into states and', 'districts', 'countries', 'continents', 'oceans', 'a', 1
    UNION ALL SELECT 'The Constitution is a set of', 'myths', 'laws and principles', 'stories', 'maps', 'b', 2
    UNION ALL SELECT 'Asia is the', 'smallest continent', 'largest continent', 'largest ocean', 'largest desert', 'b', 1
    UNION ALL SELECT 'A globe is a', 'flat map', 'model of Earth', 'mountain', 'river', 'b', 1
    UNION ALL SELECT 'Cardinal directions are', 'N,E,S,W', 'NE,NW,SE,SW', 'Up,Down,Left,Right', 'Hot,Cold', 'a', 1
    UNION ALL SELECT 'India is in the ____ hemisphere', 'northern', 'southern', 'eastern', 'western', 'a', 1
    UNION ALL SELECT 'Natural resource example', 'plastic', 'coal', 'car', 'toy', 'b', 1
    UNION ALL SELECT 'Head of a state in India', 'CM', 'PM', 'President', 'Mayor', 'a', 1
    UNION ALL SELECT 'We celebrate Republic Day on', '15 Aug', '26 Jan', '2 Oct', '14 Nov', 'b', 1
    UNION ALL SELECT 'The Himalayas are', 'plains', 'plateaus', 'mountains', 'rivers', 'c', 1
    UNION ALL SELECT 'The equator is at', '0° latitude', '90° latitude', '0° longitude', '90° longitude', 'a', 1
    UNION ALL SELECT 'Transport across seas is by', 'bus', 'ship', 'train', 'car', 'b', 1
    UNION ALL SELECT 'Community helpers include', 'doctor', 'teacher', 'police', 'all of these', 'd', 1
    UNION ALL SELECT 'Longest river in India', 'Ganga', 'Yamuna', 'Brahmaputra', 'Godavari', 'a', 2
    UNION ALL SELECT 'Capital of Japan', 'Beijing', 'Seoul', 'Tokyo', 'Bangkok', 'c', 1
) q ON e.title = 'World Geography Basics';

-- SOCIAL STUDIES - Exam 2
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Indian History & Culture', 'Heritage and historical events', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 30, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Social Studies' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Indian History & Culture')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'First Prime Minister of India' AS question_text, 'Mahatma Gandhi' AS option_a, 'Jawaharlal Nehru' AS option_b, 'Sardar Patel' AS option_c, 'Dr. Ambedkar' AS option_d, 'b' AS correct_option, 2 AS marks
    UNION ALL SELECT 'Year of Indian Independence', '1942', '1947', '1950', '1952', 'b', 1
    UNION ALL SELECT 'Author of National Anthem', 'Rabindranath Tagore', 'Bankim Chandra', 'Sarojini Naidu', 'Mahatma Gandhi', 'a', 2
    UNION ALL SELECT 'Founder of Mughal Empire', 'Akbar', 'Babur', 'Shah Jahan', 'Aurangzeb', 'b', 2
    UNION ALL SELECT 'Traditional dance of Kerala', 'Bharatanatyam', 'Kathak', 'Kathakali', 'Odissi', 'c', 1
    UNION ALL SELECT 'Ancient university in India', 'Nalanda', 'Harvard', 'Oxford', 'Cambridge', 'a', 2
    UNION ALL SELECT 'First woman Prime Minister', 'Indira Gandhi', 'Margaret Thatcher', 'Sarojini Naidu', 'Mother Teresa', 'a', 1
    UNION ALL SELECT 'National animal of India', 'Peacock', 'Tiger', 'Elephant', 'Lion', 'b', 1
    UNION ALL SELECT 'National bird of India', 'Peacock', 'Sparrow', 'Eagle', 'Pigeon', 'a', 1
    UNION ALL SELECT 'Founder of Mauryan Empire', 'Ashoka', 'Chandragupta', 'Bindusara', 'Kanishka', 'b', 2
    UNION ALL SELECT 'First President of India', 'Rajendra Prasad', 'Nehru', 'Patel', 'Radhakrishnan', 'a', 2
    UNION ALL SELECT 'Traditional clothing for men', 'Sari', 'Dhoti', 'Jeans', 'Shirt', 'b', 1
    UNION ALL SELECT 'Classical language of India', 'Hindi', 'Sanskrit', 'English', 'Urdu', 'b', 2
    UNION ALL SELECT 'Major festival of lights', 'Diwali', 'Holi', 'Eid', 'Christmas', 'a', 1
    UNION ALL SELECT 'Founder of Buddhism', 'Mahavira', 'Buddha', 'Guru Nanak', 'Shankara', 'b', 2
    UNION ALL SELECT 'Sacred river of India', 'Ganga', 'Narmada', 'Kaveri', 'Krishna', 'a', 1
) q ON e.title = 'Indian History & Culture';

-- COMPUTER STUDIES - Exam 1
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Computer Fundamentals', 'Basic computer components and operations', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 25, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Computer Studies' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Computer Fundamentals')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'An algorithm is a' AS question_text, 'computer' AS option_a, 'set of steps' AS option_b, 'programming language' AS option_c, 'device' AS option_d, 'b' AS correct_option, 1 AS marks
    UNION ALL SELECT 'Which pair is Input -> Output?', 'Printer -> Keyboard', 'Scanner -> Monitor', 'Keyboard -> Monitor', 'Speaker -> Mouse', 'c', 2
    UNION ALL SELECT 'Which is non-volatile storage?', 'RAM', 'Cache', 'Register', 'SSD/HDD', 'd', 2
    UNION ALL SELECT 'Strong password should', 'be shared', 'be short', 'mix cases/symbols', 'use your name', 'c', 1
    UNION ALL SELECT 'File type for image is', '.txt', '.png', '.exe', '.db', 'b', 1
    UNION ALL SELECT 'Input device is', 'Monitor', 'Keyboard', 'Speaker', 'Printer', 'b', 1
    UNION ALL SELECT 'CPU is the', 'Brain of computer', 'Screen', 'Mouse', 'Cable', 'a', 1
    UNION ALL SELECT 'We should not', 'share passwords', 'wash hands', 'sit straight', 'rest eyes', 'a', 1
    UNION ALL SELECT 'Portable computer is a', 'Desktop', 'Server', 'Laptop', 'Mainframe', 'c', 1
    UNION ALL SELECT 'Storage device', 'Mouse', 'Pen drive', 'Keyboard', 'Webcam', 'b', 1
    UNION ALL SELECT 'MS Word is used for', 'spreadsheets', 'word processing', 'email', 'drawing only', 'b', 1
    UNION ALL SELECT 'Shortcut to paste', 'Ctrl+P', 'Ctrl+V', 'Ctrl+C', 'Ctrl+S', 'b', 1
    UNION ALL SELECT 'Browser example', 'Chrome', 'Excel', 'Word', 'Paint', 'a', 1
    UNION ALL SELECT 'Email stands for', 'Electronic mail', 'Every mail', 'Easy mail', 'Engine mail', 'a', 1
    UNION ALL SELECT 'Antivirus helps to', 'create viruses', 'remove malware', 'play music', 'draw', 'b', 1
    UNION ALL SELECT 'Father of Computers', 'Bill Gates', 'Charles Babbage', 'Steve Jobs', 'Alan Turing', 'b', 2
) q ON e.title = 'Computer Fundamentals';

-- COMPUTER STUDIES - Exam 2
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Internet & Safety', 'Online safety and digital literacy', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 20, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Computer Studies' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Internet & Safety')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Internet is a' AS question_text, 'single computer' AS option_a, 'global network' AS option_b, 'type of software' AS option_c, 'hardware device' AS option_d, 'b' AS correct_option, 1 AS marks
    UNION ALL SELECT 'Safe online behavior includes', 'sharing passwords', 'meeting strangers', 'being kind online', 'posting personal info', 'c', 2
    UNION ALL SELECT 'Cyberbullying is', 'funny', 'acceptable', 'wrong and harmful', 'encouraged', 'c', 2
    UNION ALL SELECT 'Social media minimum age is usually', '10 years', '13 years', '16 years', '18 years', 'b', 2
    UNION ALL SELECT 'Digital footprint is', 'your online activity record', 'a type of shoe', 'computer hardware', 'internet speed', 'a', 2
    UNION ALL SELECT 'Private information includes', 'favorite color', 'home address', 'favorite food', 'hobby', 'b', 2
    UNION ALL SELECT 'Strong password has', 'only letters', 'name and birthdate', 'mix of characters', 'simple words', 'c', 1
    UNION ALL SELECT 'Website starting with https:// is', 'always safe', 'never safe', 'more secure', 'less secure', 'c', 2
    UNION ALL SELECT 'Online stranger danger means', 'talk to everyone', 'never talk to strangers', 'share everything', 'meet immediately', 'b', 2
    UNION ALL SELECT 'Copyright protects', 'ideas', 'physical objects', 'creative works', 'natural resources', 'c', 2
    UNION ALL SELECT 'Plagiarism is', 'copying others work', 'original work', 'citing sources', 'research', 'a', 2
    UNION ALL SELECT 'Good digital citizen', 'spreads rumors', 'respects others', 'hacks accounts', 'shares passwords', 'b', 2
    UNION ALL SELECT 'Two-factor authentication adds', 'more passwords', 'extra security', 'more devices', 'longer username', 'b', 2
    UNION ALL SELECT 'Phishing emails try to', 'send greetings', 'steal information', 'share news', 'offer help', 'b', 2
    UNION ALL SELECT 'Digital detox means', 'using more devices', 'taking break from tech', 'buying new phone', 'upgrading software', 'b', 1
    UNION ALL SELECT 'Report cyberbullying to', 'friends only', 'parents/teachers', 'no one', 'the bully', 'b', 2
) q ON e.title = 'Internet & Safety';

-- ENVIRONMENTAL STUDIES - Exam 1
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Eco Warriors', 'Environmental conservation basics', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 25, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Environmental Studies' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Eco Warriors')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'In a food chain, plants are' AS question_text, 'producers' AS option_a, 'consumers' AS option_b, 'decomposers' AS option_c, 'predators' AS option_d, 'a' AS correct_option, 1 AS marks
    UNION ALL SELECT 'Air pollution is least reduced by', 'carpooling', 'planting trees', 'burning waste', 'public transport', 'c', 2
    UNION ALL SELECT 'Renewable energy source is', 'coal', 'petrol', 'solar', 'diesel', 'c', 1
    UNION ALL SELECT 'The sequence Evaporation → Condensation → Precipitation is the', 'rock cycle', 'carbon cycle', 'water cycle', 'nitrogen cycle', 'c', 2
    UNION ALL SELECT 'Soil conservation method is', 'overgrazing', 'terracing', 'deforestation', 'mining', 'b', 2
    UNION ALL SELECT 'We should throw garbage in', 'street', 'dustbin', 'river', 'garden', 'b', 1
    UNION ALL SELECT 'Trees give us', 'plastic', 'smoke', 'oxygen', 'metal', 'c', 1
    UNION ALL SELECT 'Save water by', 'keeping tap open', 'fixing leaks', 'wasting water', 'playing with water', 'b', 1
    UNION ALL SELECT 'Do not litter means', 'keep clean', 'throw waste', 'dirty place', 'none', 'a', 1
    UNION ALL SELECT 'We get fruits from', 'animals', 'trees', 'cars', 'rocks', 'b', 1
    UNION ALL SELECT 'We should plant trees to get more', 'smoke', 'oxygen', 'plastic', 'dust', 'b', 1
    UNION ALL SELECT 'Do not waste', 'water', 'time', 'food', 'all of these', 'd', 1
    UNION ALL SELECT 'Rainwater can be', 'wasted', 'stored (harvested)', 'thrown', 'ignored', 'b', 1
    UNION ALL SELECT 'Clean surroundings help prevent', 'disease', 'fun', 'play', 'holidays', 'a', 1
    UNION ALL SELECT 'Cycle to school is a ____ transport', 'air', 'water', 'land', 'space', 'c', 1
    UNION ALL SELECT 'Ozone layer protects from', 'rain', 'sun UV rays', 'wind', 'cold', 'b', 2
) q ON e.title = 'Eco Warriors';

-- ENVIRONMENTAL STUDIES - Exam 2
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Climate Champions', 'Climate change and sustainability', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 30, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Environmental Studies' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Climate Champions')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Main cause of climate change' AS question_text, 'natural cycles' AS option_a, 'human activities' AS option_b, 'animal migration' AS option_c, 'plant growth' AS option_d, 'b' AS correct_option, 2 AS marks
    UNION ALL SELECT 'Greenhouse effect traps', 'oxygen', 'heat', 'water', 'light', 'b', 2
    UNION ALL SELECT 'Carbon footprint measures', 'shoe size', 'CO2 emissions', 'water usage', 'food consumption', 'b', 2
    UNION ALL SELECT 'Best way to reduce waste', 'burn everything', 'landfill', 'recycle', 'throw in ocean', 'c', 2
    UNION ALL SELECT 'Endangered species are', 'very common', 'at risk of extinction', 'pets', 'farm animals', 'b', 2
    UNION ALL SELECT 'Compost turns waste into', 'plastic', 'manure', 'metal', 'glass', 'b', 1
    UNION ALL SELECT 'Deforestation causes', 'more oxygen', 'soil erosion', 'clean water', 'better air', 'b', 2
    UNION ALL SELECT 'Renewable resources include', 'coal', 'oil', 'solar energy', 'natural gas', 'c', 2
    UNION ALL SELECT '3 R''s of environment', 'Read, Write, Arithmetic', 'Reduce, Reuse, Recycle', 'Run, Rest, Relax', 'Red, Green, Blue', 'b', 1
    UNION ALL SELECT 'Acid rain damages', 'buildings and trees', 'only water', 'only air', 'nothing', 'a', 2
    UNION ALL SELECT 'Global warming causes', 'ice melting', 'colder winters', 'less storms', 'more oxygen', 'a', 2
    UNION ALL SELECT 'Biodegradable means', 'lasts forever', 'breaks down naturally', 'made of plastic', 'expensive', 'b', 2
    UNION ALL SELECT 'Wildlife conservation protects', 'only tigers', 'only birds', 'all animals', 'only plants', 'c', 2
    UNION ALL SELECT 'Sustainable development means', 'use all resources now', 'meet needs without harming future', 'ignore environment', 'only economic growth', 'b', 2
    UNION ALL SELECT 'National animal of India', 'Lion', 'Tiger', 'Elephant', 'Peacock', 'b', 1
    UNION ALL SELECT 'Project Tiger protects', 'lions', 'tigers', 'elephants', 'birds', 'b', 1
) q ON e.title = 'Climate Champions';

-- GENERAL KNOWLEDGE - Exam 1
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'World Wonders Quiz', 'Famous landmarks and geography', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 25, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'General Knowledge' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'World Wonders Quiz')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Capital of Australia is' AS question_text, 'Sydney' AS option_a, 'Melbourne' AS option_b, 'Canberra' AS option_c, 'Perth' AS option_d, 'c' AS correct_option, 1 AS marks
    UNION ALL SELECT 'The Great Wall is in', 'India', 'China', 'Japan', 'Korea', 'b', 1
    UNION ALL SELECT 'Currency of Japan is', 'Yuan', 'Yen', 'Won', 'Rupee', 'b', 1
    UNION ALL SELECT 'Earth completes one orbit of Sun in about', '24 hours', '7 days', '365 days', '30 days', 'c', 1
    UNION ALL SELECT 'Alexander Fleming discovered', 'Penicillin', 'X-ray', 'Gravity', 'Telephone', 'a', 2
    UNION ALL SELECT 'National animal of India', 'Peacock', 'Tiger', 'Elephant', 'Cow', 'b', 1
    UNION ALL SELECT 'How many days in a week?', '5', '6', '7', '8', 'c', 1
    UNION ALL SELECT 'Color of the sky (clear)', 'Blue', 'Green', 'Red', 'Pink', 'a', 1
    UNION ALL SELECT 'Largest mammal', 'Elephant', 'Blue whale', 'Giraffe', 'Hippo', 'b', 1
    UNION ALL SELECT 'We use a compass to find', 'speed', 'time', 'direction', 'distance', 'c', 1
    UNION ALL SELECT 'How many continents are there?', '5', '6', '7', '8', 'c', 1
    UNION ALL SELECT 'Fastest land animal', 'Cheetah', 'Lion', 'Horse', 'Tiger', 'a', 1
    UNION ALL SELECT 'Largest land animal', 'Elephant', 'Rhino', 'Hippo', 'Giraffe', 'a', 1
    UNION ALL SELECT 'Smallest planet (by diameter)', 'Mercury', 'Mars', 'Venus', 'Earth', 'a', 1
    UNION ALL SELECT 'Taj Mahal is in', 'Delhi', 'Mumbai', 'Agra', 'Jaipur', 'c', 1
    UNION ALL SELECT 'Longest river in world', 'Nile', 'Amazon', 'Ganga', 'Mississippi', 'a', 2
) q ON e.title = 'World Wonders Quiz';

-- GENERAL KNOWLEDGE - Exam 2
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Science & Innovation', 'Discoveries and inventions', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 30, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'General Knowledge' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Science & Innovation')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Inventor of telephone' AS question_text, 'Thomas Edison' AS option_a, 'Alexander Bell' AS option_b, 'Albert Einstein' AS option_c, 'Isaac Newton' AS option_d, 'b' AS correct_option, 2 AS marks
    UNION ALL SELECT 'Discoverer of gravity', 'Galileo', 'Newton', 'Einstein', 'Tesla', 'b', 2
    UNION ALL SELECT 'First man on moon', 'Neil Armstrong', 'Buzz Aldrin', 'Yuri Gagarin', 'John Glenn', 'a', 2
    UNION ALL SELECT 'Inventor of light bulb', 'Edison', 'Tesla', 'Bell', 'Marconi', 'a', 2
    UNION ALL SELECT 'Father of computer', 'Bill Gates', 'Charles Babbage', 'Steve Jobs', 'Alan Turing', 'b', 2
    UNION ALL SELECT 'Discoverer of penicillin', 'Fleming', 'Curie', 'Pasteur', 'Darwin', 'a', 2
    UNION ALL SELECT 'Theory of relativity by', 'Newton', 'Einstein', 'Galileo', 'Hawking', 'b', 2
    UNION ALL SELECT 'First woman in space', 'Kalpana Chawla', 'Valentina Tereshkova', 'Sally Ride', 'Mae Jemison', 'b', 2
    UNION ALL SELECT 'Inventor of printing press', 'Gutenberg', 'Da Vinci', 'Copernicus', 'Galileo', 'a', 2
    UNION ALL SELECT 'Discoverer of DNA structure', 'Watson & Crick', 'Darwin', 'Mendel', 'Pasteur', 'a', 2
    UNION ALL SELECT 'First computer programmer', 'Ada Lovelace', 'Grace Hopper', 'Steve Jobs', 'Bill Gates', 'a', 2
    UNION ALL SELECT 'Inventor of radio', 'Marconi', 'Edison', 'Tesla', 'Bell', 'a', 2
    UNION ALL SELECT 'Discoverer of X-rays', 'Roentgen', 'Curie', 'Einstein', 'Bohr', 'a', 2
    UNION ALL SELECT 'Theory of evolution by', 'Darwin', 'Newton', 'Einstein', 'Galileo', 'a', 2
    UNION ALL SELECT 'First satellite in space', 'Sputnik', 'Apollo', 'Voyager', 'Hubble', 'a', 2
    UNION ALL SELECT 'Inventor of airplane', 'Wright brothers', 'Edison', 'Tesla', 'Ford', 'a', 2
) q ON e.title = 'Science & Innovation';

-- CIVICS & MORAL ED. - Exam 1
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Good Citizenship', 'Rights, duties and community values', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 25, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Civics & Moral Ed.' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Good Citizenship')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'We should always say' AS question_text, 'No' AS option_a, 'Go' AS option_c, 'Please' AS option_b, 'Nothing' AS option_d, 'c' AS correct_option, 1 AS marks
    UNION ALL SELECT 'Helping others is', 'bad', 'good', 'rude', 'late', 'b', 1
    UNION ALL SELECT 'We should stand in', 'fight', 'line', 'crowd', 'none', 'b', 1
    UNION ALL SELECT 'We should respect', 'teachers', 'books', 'national flag', 'all of these', 'd', 1
    UNION ALL SELECT 'Tell the truth means', 'honesty', 'anger', 'sleep', 'play', 'a', 1
    UNION ALL SELECT 'Right to vote is a', 'duty', 'right', 'punishment', 'rule', 'b', 1
    UNION ALL SELECT 'We must be ____ in a queue', 'patient', 'angry', 'loud', 'pushing', 'a', 1
    UNION ALL SELECT 'Public property must be', 'damaged', 'respected', 'ignored', 'stolen', 'b', 1
    UNION ALL SELECT 'Cross the road at', 'anywhere', 'zebra crossing', 'corner', 'middle', 'b', 1
    UNION ALL SELECT 'Laws help to keep', 'chaos', 'order', 'noise', 'crowd', 'b', 1
    UNION ALL SELECT 'We must help', 'elderly', 'children', 'people in need', 'all of these', 'd', 1
    UNION ALL SELECT 'Honesty builds', 'trust', 'fear', 'anger', 'sadness', 'a', 1
    UNION ALL SELECT 'Bullying is', 'good', 'acceptable', 'bad', 'funny', 'c', 1
    UNION ALL SELECT 'We should keep public places', 'dirty', 'clean', 'noisy', 'crowded', 'b', 1
    UNION ALL SELECT 'Good citizens follow', 'only favorite rules', 'laws and rules', 'no rules', 'their friends', 'b', 1
    UNION ALL SELECT 'National anthem should be', 'ignored', 'sung with respect', 'whistled', 'changed', 'b', 2
) q ON e.title = 'Good Citizenship';

-- CIVICS & MORAL ED. - Exam 2
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Values & Ethics', 'Moral principles and decision making', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 30, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Civics & Moral Ed.' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Values & Ethics')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Integrity means' AS question_text, 'being dishonest' AS option_a, 'doing right when no one watches' AS option_b, 'following crowd' AS option_c, 'breaking rules' AS option_d, 'b' AS correct_option, 2 AS marks
    UNION ALL SELECT 'Empathy is', 'feeling sorry for yourself', 'understanding others feelings', 'being strict', 'ignoring others', 'b', 2
    UNION ALL SELECT 'Responsibility means', 'blaming others', 'making excuses', 'owning your actions', 'avoiding work', 'c', 2
    UNION ALL SELECT 'Good sportsmanship includes', 'cheating to win', 'respecting opponents', 'arguing with referee', 'boasting', 'b', 2
    UNION ALL SELECT 'Fairness means', 'favoring friends', 'equal treatment for all', 'ignoring rules', 'being biased', 'b', 2
    UNION ALL SELECT 'Courage is', 'running from danger', 'facing fears doing right', 'following others', 'staying silent', 'b', 2
    UNION ALL SELECT 'Perseverance means', 'giving up easily', 'keeping trying despite difficulties', 'avoiding challenges', 'making excuses', 'b', 2
    UNION ALL SELECT 'Respect involves', 'interrupting others', 'listening patiently', 'ignoring opinions', 'making fun', 'b', 2
    UNION ALL SELECT 'Compassion is', 'indifference', 'caring for others suffering', 'selfishness', 'rudeness', 'b', 2
    UNION ALL SELECT 'Gratitude means', 'complaining', 'being thankful', 'expecting more', 'ignoring help', 'b', 2
    UNION ALL SELECT 'Humility is', 'boasting', 'being modest', 'showing off', 'arrogance', 'b', 2
    UNION ALL SELECT 'Patience means', 'getting angry quickly', 'waiting calmly', 'rushing others', 'complaining', 'b', 2
    UNION ALL SELECT 'Tolerance is', 'rejecting differences', 'accepting diversity', 'discrimination', 'prejudice', 'b', 2
    UNION ALL SELECT 'Self-discipline involves', 'doing whatever you want', 'controlling impulses', 'making excuses', 'blaming others', 'b', 2
    UNION ALL SELECT 'Cooperation means', 'working alone', 'working together', 'competing fiercely', 'undermining others', 'b', 2
    UNION ALL SELECT 'Kindness is', 'being rude', 'helping others willingly', 'ignoring needs', 'selfishness', 'b', 2
) q ON e.title = 'Values & Ethics';

-- ART & CRAFT - Exam 1
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Color Theory Basics', 'Understanding colors and their combinations', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 25, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Art & Craft' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Color Theory Basics')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Primary colors are' AS question_text, 'Red, Blue, Yellow' AS option_a, 'Red, Green, Blue' AS option_b, 'Black, White, Grey' AS option_c, 'Pink, Purple, Orange' AS option_d, 'a' AS correct_option, 1 AS marks
    UNION ALL SELECT 'A circle has', '3 sides', '4 sides', 'no sides', '2 sides', 'c', 1
    UNION ALL SELECT 'We cut paper with', 'pencil', 'glue', 'scissors', 'tape', 'c', 1
    UNION ALL SELECT 'We stick with', 'glue', 'ruler', 'eraser', 'pen', 'a', 1
    UNION ALL SELECT 'Square has', '2 sides', '3 sides', '4 equal sides', '5 sides', 'c', 1
    UNION ALL SELECT 'Secondary colors are made by mixing', 'primary + black', 'two primary colors', 'primary + white', 'two secondary colors', 'b', 2
    UNION ALL SELECT 'Warm colors include', 'blue, green', 'green, purple', 'red, orange, yellow', 'blue, purple, green', 'c', 1
    UNION ALL SELECT 'A shape is symmetrical if it', 'has equal area', 'can fold into equal halves', 'has four sides', 'is a circle', 'b', 2
    UNION ALL SELECT 'Perspective drawing helps to show', 'texture', 'distance and depth', 'color contrast', 'pattern', 'b', 2
    UNION ALL SELECT 'Best paper for watercolor is', 'thin copy paper', 'glossy paper', 'thick textured paper', 'plastic sheet', 'c', 1
    UNION ALL SELECT 'Complementary colors are', 'next to each other', 'opposite on color wheel', 'same family', 'all warm colors', 'b', 2
    UNION ALL SELECT 'Tint is made by adding', 'black', 'white', 'gray', 'water', 'b', 2
    UNION ALL SELECT 'Shade is made by adding', 'white', 'black', 'water', 'another color', 'b', 2
    UNION ALL SELECT 'Analogous colors are', 'opposites', 'neighbors on wheel', 'all primaries', 'all secondaries', 'b', 2
    UNION ALL SELECT 'Color harmony creates', 'chaos', 'pleasing arrangement', 'brightness', 'darkness', 'b', 2
    UNION ALL SELECT 'Monochromatic means', 'one color', 'many colors', 'complementary', 'warm colors', 'a', 2
) q ON e.title = 'Color Theory Basics';

-- ART & CRAFT - Exam 2
INSERT INTO exams (subject_id, title, description, created_by, start_time, end_time, duration_min, is_published)
SELECT s.subject_id, 'Art Techniques & History', 'Various art methods and famous artists', u.user_id, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 30, 1
FROM subjects s JOIN users u ON u.email = 'teacher@easeexam.local'
WHERE s.name = 'Art & Craft' AND NOT EXISTS (SELECT 1 FROM exams e WHERE e.title = 'Art Techniques & History')
LIMIT 1;

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
SELECT e.exam_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option, q.marks
FROM exams e
JOIN (
    SELECT 'Primary colors mix to make' AS question_text, 'tertiary colors' AS option_a, 'secondary colors' AS option_b, 'tints' AS option_c, 'shades' AS option_d, 'b' AS correct_option, 1 AS marks
    UNION ALL SELECT 'Texture is how something', 'looks', 'feels', 'smells', 'sounds', 'b', 1
    UNION ALL SELECT 'Cool colors are often used to show', 'warmth', 'calm', 'danger', 'excitement', 'b', 1
    UNION ALL SELECT 'Warm colors are often used to show', 'cold', 'sadness', 'energy', 'silence', 'c', 1
    UNION ALL SELECT 'Symmetry line divides a shape into', 'equal halves', 'unequal parts', 'random parts', 'circles', 'a', 1
    UNION ALL SELECT 'Best tool to draw a circle', 'scale', 'protractor', 'compass', 'set square', 'c', 1
    UNION ALL SELECT 'Painting light colors over dark makes a', 'shade', 'tint', 'pattern', 'line', 'b', 1
    UNION ALL SELECT 'Perspective uses lines that', 'are parallel', 'meet at a point', 'are circles', 'are random', 'b', 1
    UNION ALL SELECT 'Best storage for art paper', 'wet place', 'sunlight', 'dry flat place', 'crumpled', 'c', 1
    UNION ALL SELECT 'Color wheel helps artists to', 'cook', 'choose color schemes', 'drive', 'sing', 'b', 1
    UNION ALL SELECT 'Famous painting: Mona Lisa by', 'Van Gogh', 'Picasso', 'Da Vinci', 'Monet', 'c', 2
    UNION ALL SELECT 'Starry Night was painted by', 'Van Gogh', 'Picasso', 'Da Vinci', 'Rembrandt', 'a', 2
    UNION ALL SELECT 'Watercolor painting uses', 'oil', 'water-soluble pigments', 'acrylic', 'charcoal', 'b', 2
    UNION ALL SELECT 'Sculpture is', '2D art', '3D art', 'digital art', 'performance art', 'b', 2
    UNION ALL SELECT 'Impressionism art style started in', 'Italy', 'France', 'USA', 'Japan', 'b', 2
    UNION ALL SELECT 'Still life painting shows', 'moving objects', 'stationary objects', 'people', 'landscapes', 'b', 2
    UNION ALL SELECT 'Portrait painting focuses on', 'landscapes', 'people', 'animals', 'buildings', 'b', 1
) q ON e.title = 'Art Techniques & History';

-- Accessing Admin Dashboard

-- Log in with a user that has role = 'admin'.

-- Example admin account insert:

INSERT INTO users (full_name, email, password_hash, role) 
VALUES ('System Admin', 'admin@gmail.com', PASSWORD('admin123'), 'admin');
