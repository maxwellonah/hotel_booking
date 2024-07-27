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

    // Calculate total days
    $date1 = new DateTime($checkIn);
    $date2 = new DateTime($checkOut);
    $totalDays = $date1->diff($date2)->days;

    // Get today's date
    $today = new DateTime();
    $todayStr = $today->format('Y-m-d');

    // Insert into users table
    $sql = "INSERT INTO users (name, email, phone) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Prepare failed for users table: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $phone);
    if (!mysqli_stmt_execute($stmt)) {
        die("Error inserting user: " . mysqli_error($conn));
    }

    $userId = mysqli_insert_id($conn);

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

        // Insert into reservations table with status 'pending'
        $sql = "INSERT INTO reservations (user_id, room_id, check_in_date, check_out_date, status, total_adults, total_children, total_guests, payment_type, total_days) 
                VALUES (?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            die("Prepare failed for reservations table: " . mysqli_error($conn));
        }

        $totalGuests = $totalAdults + $totalChildren;
        mysqli_stmt_bind_param($stmt, "iissiiisi", $userId, $roomId, $checkIn, $checkOut, $totalAdults, $totalChildren, $totalGuests, $paymentType, $totalDays);

        if (mysqli_stmt_execute($stmt)) {
            // Insert into approved_bookings table with status 'pending'
            $sql = "INSERT INTO approved_bookings (user_id, room_id, check_in, check_out, status) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if (!$stmt) {
                die("Prepare failed for approved_bookings table: " . mysqli_error($conn));
            }

            $status = 'pending'; // Set the status
            mysqli_stmt_bind_param($stmt, "iisss", $userId, $roomId, $checkIn, $checkOut, $status);

            if (mysqli_stmt_execute($stmt)) {
                // Get the ID of the newly inserted record in approved_bookings
                $approvedBookingId = mysqli_insert_id($conn);

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

                // Redirect to bookaroom page with approved booking ID
                header('Location: rbooking.php?php?successful&booking_id=' . $approvedBookingId);
                exit();
            } else {
                die("Error inserting into approved_bookings table: " . mysqli_error($conn));
            }
        } else {
            die("Error in the booking process: " . mysqli_error($conn));
        }
    } else {
        header('Location: rbooking.php?norooms');
    }

    // Close statements
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($updateStmt);
    mysqli_stmt_close($updateRoomStatusStmt);
}

mysqli_close($conn);
?>
