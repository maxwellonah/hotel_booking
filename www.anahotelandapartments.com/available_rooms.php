<?php
// Start the session
include 'db.php'; // Include database connection

// Fetch available rooms and their types
$sql = "SELECT room_id, room_number, room_type, price_per_night FROM rooms WHERE status = 'available'";
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
    <title>Available Rooms</title>
    <?php include 'css.php'; ?>
</head>
<body>

<h2>Available Rooms</h2>

<table border="1">
    <thead>
        <tr>
            <th>Room ID</th>
            <th>Room N0.</th>
            <th>Room Type</th>
            <th>Price per Night</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['room_id']; ?></td>
                <td><?php echo $row['room_number']; ?></td>
                <td><?php echo $row['room_type']; ?></td>
                <td><?php echo $row['price_per_night']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php mysqli_close($conn); ?>
</body>
</html>
