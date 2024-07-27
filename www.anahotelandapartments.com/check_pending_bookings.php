<?php
include 'db.php'; // Include database connection

// Start the session
session_start();

// Check if the user is an admin (modify this as per your authentication logic)
if (!isset($_SESSION['admin_id'])) {
    header('Location: sign-in-up.php');
    exit();
}

// Update the status of transfer bookings older than 30 minutes
$sql = "UPDATE approved_bookings ab
        JOIN payments p ON ab.id = p.booking_id
        SET ab.status = 'declined' 
        WHERE ab.status = 'pending' 
        AND p.payment_method = 'transfer' 
        AND ab.created_at < NOW() - INTERVAL 30 MINUTE";

if (mysqli_query($conn, $sql)) {
    echo "Pending transfer bookings updated successfully.";
} else {
    echo "Error updating bookings: " . mysqli_error($conn);
}

// Update the status of rooms if the check-in date has passed and the booking hasn't been approved
$sql = "UPDATE approved_bookings ab
        JOIN rooms r ON ab.room_id = r.room_id
        SET ab.status = 'declined', r.status = 'available'
        WHERE ab.status = 'pending' 
        AND ab.check_in < CURDATE()";

if (mysqli_query($conn, $sql)) {
    echo "Pending bookings with passed check-in dates updated successfully.";
} else {
    echo "Error updating bookings with passed check-in dates: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
