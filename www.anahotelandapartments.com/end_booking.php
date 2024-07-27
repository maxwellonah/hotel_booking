<?php
include 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];

    // Update the booking status and room availability
    $updateSql = "UPDATE rooms 
                  SET status = 'available' 
                  WHERE room_id = (
                      SELECT room_id FROM approved_bookings WHERE id = ?
                  )";

    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, 'i', $booking_id);
    mysqli_stmt_execute($stmt);

    // Delete the booking
    $deleteSql = "DELETE FROM approved_bookings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteSql);
    mysqli_stmt_bind_param($stmt, 'i', $booking_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header('Location: admin.php');
    exit();
}
?>
