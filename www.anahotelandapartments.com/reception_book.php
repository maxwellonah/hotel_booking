<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $roomType = $_POST['room-type'];
    $checkIn = $_POST['check_in_date'];
    $checkOut = $_POST['check_out_date'];
    $totalAdults = $_POST['total_adults'];
    $totalChildren = $_POST['total_children'];
    $paymentType = $_POST['payment-type'];

    // Insert into users table
    $sql = "INSERT INTO users (name, email, phone) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $phone);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error inserting user: " . mysqli_error($conn));
    }

    $userId = mysqli_insert_id($conn);

    // Insert into reservations table
    $sql = "INSERT INTO reservations (user_id, room_type, check_in_date, check_out_date, total_adults, total_children, payment_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isssiis", $userId, $roomType, $checkIn, $checkOut, $totalAdults, $totalChildren, $paymentType);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error inserting reservation: " . mysqli_error($conn));
    }

    
}

mysqli_close($conn);
?>
