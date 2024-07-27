<?php
include 'db.php';
session_start();

// Check if the user is logged in and if the user is an admin (user_type == 1)
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    header('Location: sign-in-up.php');
    exit();
} else {
    error_log("User ID: " . $_SESSION['user_id']);
    error_log("User Name: " . $_SESSION['name']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="white">

    <link rel="icon" type="image/ico" href="img/favicon2.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- Add your custom styles if any -->

    <title>Admin Panel - Add Room</title>
</head>
<body >
<?php include 'header.php'; ?>
    <div class="row">
        <div class="col-md-3">
            <div class="container mt-5">
                <h3 class="text-center">Add New Room</h3>
                <section class="text-dark mt-4">
                    <form action="add_room.php" method="post" id="">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="room_number">Room Number:</label>
                                <input type="text" class="form-control" id="room_number" name="room_number" required>
                            </div>
                            <div class="col-md-6">
                                <label for="type">Type:</label>
                                <input type="text" class="form-control" id="type" name="type" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="capacity">Capacity:</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" required>
                            </div>
                            <div class="col-md-6">
                                <label for="price_per_night">Price per Night:</label>
                                <input type="number" class="form-control" id="price_per_night" name="price_per_night" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="status">Status:</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option selected disabled>Select Status</option>
                                    <option value="available">Available</option>
                                    <option value="booked">Booked</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <button name="register" class="btn btn-primary mt-3 rounded-pill" type="submit" value="Add Room">Add Room</button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-9">
                    <div class="container">
                        <?php
                        include 'admin_approve_bookings.php';
                        ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <form action="generate_report.php" method="post">
                        <label for="report_date">Select Date:</label>
                        <input type="date" id="report_date" name="report_date" required>
                        <button type="submit">Download Report</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="container">
                        <?php
                        include 'available_rooms.php';
                        ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="container">
                        <?php
                        include 'booked_rooms.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


