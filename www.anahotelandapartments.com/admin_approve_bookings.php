<?php
// Start the session
include 'db.php'; // Include database connection

// Fetch pending bookings with room price per night
$sql = "SELECT ab.*, r.price_per_night 
        FROM approved_bookings ab
        JOIN rooms r ON ab.room_id = r.room_id
        WHERE ab.status = 'pending'";

$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Approve Bookings</title>
    <?php include 'css.php'; ?>
</head>
<body>

<h2>Pending Bookings</h2>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Room ID</th>
            <th>Price per Night</th>
            <th>Total Price</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
            // Calculate the number of nights
            $checkIn = new DateTime($row['check_in']);
            $checkOut = new DateTime($row['check_out']);
            $totalNights = $checkIn->diff($checkOut)->days;

            // Calculate the total price
            $totalPrice = $totalNights * $row['price_per_night'];
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['room_id']; ?></td>
                <td><?php echo $row['price_per_night']; ?></td>
                <td><?php echo $totalPrice; ?></td>
                <td><?php echo $row['check_in']; ?></td>
                <td><?php echo $row['check_out']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <form action="admin_approve_booking_action.php" method="POST">
                        <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="approve">Approve</button>
                        <button type="submit" name="decline">Decline</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php mysqli_close($conn); ?>
</body>
</html>
