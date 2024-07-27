<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $capacity = $_POST['capacity'];
    $price_per_night = $_POST['price_per_night'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $sql = "INSERT INTO Rooms (room_number, type, capacity, price_per_night, description, status)
            VALUES ('$room_number', '$type', $capacity, $price_per_night, '$description', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "Room added successfully!";
        header("Location: http://localhost/anahanda/www.anahotelandapartments.com/admin.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
