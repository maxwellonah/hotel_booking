<?php
include 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $extra_days = $_POST['extra_days'];

    // Debug: Print values being passed to the query
    // echo "Booking ID: $booking_id<br>";
    // echo "Extra Days: $extra_days<br>";

    // Update the booking check-out date
    $updateSql = "UPDATE approved_bookings 
                  SET check_out = DATE_ADD(check_out, INTERVAL ? DAY) 
                  WHERE id = ?";

    // Prepare and execute the statement
    if ($stmt = mysqli_prepare($conn, $updateSql)) {
        mysqli_stmt_bind_param($stmt, 'ii', $extra_days, $booking_id);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Check-out date extended successfully.";
        } else {
            echo "No rows affected.";
        }
        mysqli_stmt_close($stmt);
    } else {
        // Handle errors
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    mysqli_close($conn);

    header('Location: admin.php');
    exit();
}
?>
