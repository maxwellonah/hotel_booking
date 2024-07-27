<?php
include 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Validate user input
    if (empty($name) || empty($email) || empty($password) || empty($address) || empty($phone)) {
        header('Location: sign-in-up.php?error=empty_fields');
        exit();
    }

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $checkEmail);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        header('Location: sign-in-up.php?error=email_exists');
        exit();
    }

    // Hash password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert user data
    $sql = "INSERT INTO users (name, email, password, address, phone) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $hashedPassword, $address, $phone);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header('Location: sign-in-up.php?success=registration');
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
