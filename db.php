<?php
$host = "localhost";
$db   = "easeexam";
$user = "root";        // XAMPP default
$pass = "";            // empty by default in XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected!"; // test
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
