<?php
include 'db.php'; // Include database connection

// Check for rooms where the checkout date has passed and update their status
$updateSql = "UPDATE rooms 
              SET status = 'available' 
              WHERE room_id IN (
                  SELECT room_id FROM approved_bookings 
                  WHERE status = 'approved' AND check_out < NOW()
              )";

mysqli_query($conn, $updateSql);

// Fetch booked rooms
$sql = "SELECT ab.id as booking_id, ab.room_id, r.room_number, r.room_type, r.price_per_night, ab.check_in, ab.check_out, u.name 
        FROM approved_bookings ab
        JOIN rooms r ON ab.room_id = r.room_id
        JOIN users u ON ab.user_id = u.user_id
        WHERE ab.status = 'approved'";

$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

// Debug: Print the number of rows returned
$rows_count = mysqli_num_rows($result);
if ($rows_count === 0) {
    echo "No booked rooms found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Booked Rooms</title>
    <?php include 'css.php'; ?>
</head>
<body>

<h2>Booked Rooms</h2>

<table border="1">
    <thead>
        <tr>
            <th>Room ID</th>
            <th>Room Number</th>
            <th>Room Type</th>
            <th>Price per Night</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>User Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['room_id']; ?></td>
                <td><?php echo $row['room_number']; ?></td>
                <td><?php echo $row['room_type']; ?></td>
                <td><?php echo $row['price_per_night']; ?></td>
                <td><?php echo $row['check_in']; ?></td>
                <td><?php echo $row['check_out']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td>
                    <form action="end_booking.php" method="post" style="display:inline;">
                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                        <button type="submit">End Booking</button>
                    </form>
                    <form action="extend_booking.php" method="post" style="display:inline;">
                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                        <input type="number" name="extra_days" min="1" placeholder="Days" required>
                        <button type="submit">Extend Booking</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php mysqli_close($conn); ?>
</body>
</html>
