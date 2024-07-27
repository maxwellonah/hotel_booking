<?php
include 'db.php';

$roomType = $_GET['type'];

$sql = "SELECT * FROM rooms WHERE type = ? AND availability = TRUE";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $roomType);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode($rooms);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
