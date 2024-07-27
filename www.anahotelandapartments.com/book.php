<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $roomType = $_POST['room-type'];
    $checkIn = $_POST['check_in_date'];
    $checkOut = $_POST['check_out_date'];
    $totalAdults = $_POST['total_adults'];
    $totalChildren = $_POST['total_children'];
    $paymentType = $_POST['payment-type'];

    // Calculate total days
    $date1 = new DateTime($checkIn);
    $date2 = new DateTime($checkOut);
    $totalDays = $date1->diff($date2)->days;

    // Get today's date
    $today = new DateTime();
    $todayStr = $today->format('Y-m-d');

    // Check if user exists
    $userCheckSql = "SELECT user_id FROM users WHERE user_id = ?";
    $userCheckStmt = mysqli_prepare($conn, $userCheckSql);
    mysqli_stmt_bind_param($userCheckStmt, "i", $userId);
    mysqli_stmt_execute($userCheckStmt);
    $userCheckResult = mysqli_stmt_get_result($userCheckStmt);

    if (mysqli_num_rows($userCheckResult) === 0) {
        echo "User does not exist.";
        exit;
    }

    // Check for room availability
    $sql = "SELECT room_id FROM rooms WHERE room_type = ? AND (status = 'available' OR status IS NULL) AND room_id NOT IN (
                SELECT room_id FROM reservations 
                WHERE (check_in_date < ? AND check_out_date > ?)
            ) LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("SQL error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sss", $roomType, $checkOut, $checkIn);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $availableRoom = mysqli_fetch_assoc($result);

    if ($availableRoom) {
        $roomId = $availableRoom['room_id'];

        // Insert into approved_bookings table
        $sql = "INSERT INTO approved_bookings (user_id, room_id, check_in, check_out) VALUES (?, ?, ?, ?)";
        $approvedStmt = mysqli_prepare($conn, $sql);
        if (!$approvedStmt) {
            die("SQL error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($approvedStmt, "iiss", $userId, $roomId, $checkIn, $checkOut);
        if (!mysqli_stmt_execute($approvedStmt)) {
            die("Error inserting into approved_bookings: " . mysqli_error($conn));
        }

        // Get the last inserted booking ID
        $bookingId = mysqli_insert_id($conn);

        // Insert into reservations table with status 'pending'
        $insertSql = "INSERT INTO reservations (user_id, room_id, check_in_date, check_out_date, status, total_adults, total_children, total_guests, payment_type, total_days) 
                       VALUES (?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?)";

        $insertStmt = mysqli_prepare($conn, $insertSql);
        if (!$insertStmt) {
            die("SQL error: " . mysqli_error($conn));
        }

        $totalGuests = $totalAdults + $totalChildren;

        mysqli_stmt_bind_param($insertStmt, "iissiiisi", $userId, $roomId, $checkIn, $checkOut, $totalAdults, $totalChildren, $totalGuests, $paymentType, $totalDays);

        if (mysqli_stmt_execute($insertStmt)) {
            if ($todayStr === $checkIn) {
                // Update room availability to 'booked' if check-in date is today
                $updateSql = "UPDATE rooms SET status = 'booked' WHERE room_id = ?";
                $updateStmt = mysqli_prepare($conn, $updateSql);
                if (!$updateStmt) {
                    die("SQL error: " . mysqli_error($conn));
                }

                mysqli_stmt_bind_param($updateStmt, "i", $roomId);
                if (!mysqli_stmt_execute($updateStmt)) {
                    die("Error updating room status: " . mysqli_error($conn));
                }
            }
            
            // Mark rooms as available if check-out date is today or in the past
            $updateRoomStatusSql = "UPDATE rooms r
                                    JOIN reservations res ON r.room_id = res.room_id
                                    SET r.status = 'available'
                                    WHERE res.check_out_date <= ? AND r.status = 'booked'";
            $updateRoomStatusStmt = mysqli_prepare($conn, $updateRoomStatusSql);
            if (!$updateRoomStatusStmt) {
                die("SQL error: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($updateRoomStatusStmt, "s", $todayStr);
            mysqli_stmt_execute($updateRoomStatusStmt);

            // Redirect to bookaroom page with booking ID
            header('Location: bookaroom.php?successful&booking_id=' . $bookingId);
            exit();
        } else {
            echo "Error in the booking process: " . mysqli_error($conn);
        }
    } else {
        header('Location: bookaroom.php?norooms');
    }

    // Close statements
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($approvedStmt);
    mysqli_stmt_close($insertStmt);
    mysqli_stmt_close($updateStmt);
    mysqli_stmt_close($updateRoomStatusStmt);
}

mysqli_close($conn);
?>
