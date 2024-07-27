<?php
include 'db.php'; // Include database connection

// Start session
session_start();

// Get form data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($email) || empty($password)) {
    header('Location: sign-in-up.php?error=empty_fields');
    exit();
}

// Prepare SQL statement to fetch user data by email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if user exists
if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Login successful, store user data in session
        $_SESSION['user_id'] = $user['user_id']; // Store user ID in session
        $_SESSION['name'] = $user['name']; // Store user name in session
        $_SESSION['user_type'] = $user['user_type']; // Store user type in session
        
        // Redirect based on user type
        if ($user['user_type'] == 0) {
            header('Location: bookaroom.php'); // Redirect to book a room page
        } else {
            header('Location: admin.php'); // Redirect to admin page
        }
        exit();
    } else {
        header('Location: sign-in-up.php?error=invalid_password');
        exit();
    }
} else {
    header('Location: sign-in-up.php?error=user_not_found');
    exit();
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
