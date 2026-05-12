<?php
/**
 * reset_admin.php
 * Run this file once to reset the admin password.
 * IMPORTANT: Delete this file after use for security!
 */

session_start();
include "db.php";

// New password for admin
$newPassword = "123456";

// Generate a new secure hash
$newHash = password_hash($newPassword, PASSWORD_BCRYPT);

// Update the database
$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
$success = $stmt->execute([$newHash, "admin@gmail.com"]);

if ($success) {
    echo "<h2 style='color:green;'>✅ Admin password reset successfully!</h2>";
    echo "<p><b>Email:</b> admin@gmail.com</p>";
    echo "<p><b>New Password:</b> {$newPassword}</p>";
    echo "<p style='color:red;'>⚠️ Please delete this file (reset_admin.php) immediately after logging in!</p>";
} else {
    echo "<h2 style='color:red;'>❌ Failed to reset password. Check database connection or email.</h2>";
}
