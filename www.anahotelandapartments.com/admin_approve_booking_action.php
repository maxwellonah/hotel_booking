<?php
include 'db.php'; // Include database connection
session_start(); // Ensure session is started

// Check if the user is logged in and if the user is an admin (user_type == 1)
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    header('Location: sign-in-up.php');
    exit();
}

// Get the booking ID from the form
$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;

// Determine if the action is approve or decline
if (isset($_POST['approve'])) {
    $status = 'approved';
} elseif (isset($_POST['decline'])) {
    $status = 'declined';
} else {
    die("Invalid action.");
}

// Update the booking status
$sql = "UPDATE approved_bookings SET status = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $status, $booking_id);

if (mysqli_stmt_execute($stmt)) {
    // Fetch the room ID associated with the booking
    $room_sql = "SELECT room_id FROM approved_bookings WHERE id = ?";
    $room_stmt = mysqli_prepare($conn, $room_sql);
    mysqli_stmt_bind_param($room_stmt, "i", $booking_id);
    mysqli_stmt_execute($room_stmt);
    $room_result = mysqli_stmt_get_result($room_stmt);

    if ($room = mysqli_fetch_assoc($room_result)) {
        $room_id = $room['room_id'];

        // Update room status based on booking status
        $update_room_sql = $status == 'approved' ? 
            "UPDATE rooms SET status = 'unavailable' WHERE room_id = ?" : 
            "UPDATE rooms SET status = 'available' WHERE room_id = ?";
        $update_room_stmt = mysqli_prepare($conn, $update_room_sql);
        mysqli_stmt_bind_param($update_room_stmt, "i", $room_id);
        if (!mysqli_stmt_execute($update_room_stmt)) {
            echo "Error updating room status: " . mysqli_error($conn);
        }
        mysqli_stmt_close($update_room_stmt);
    } else {
        echo "Room ID not found for booking ID: $booking_id";
    }
    mysqli_stmt_close($room_stmt);

    // Redirect to admin page after updating
    header('Location: admin.php');
    exit();
} else {
    echo "Error updating booking status: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
