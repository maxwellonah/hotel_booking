<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected date
    $selected_date = $_POST['report_date'];

    // Database connection
    $servername = "localhost";
    $username = "root"; // Update with your database username
    $password = ""; // Update with your database password
    $dbname = "hotel_db"; // Update with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "
        SELECT 
            u.name AS `Name`,
            rm.room_number AS `Room Number`,
            (r.total_days * rm.price_per_night) AS `Total Amount Paid`,
            r.check_in_date AS `Check In`,
            r.check_out_date AS `Check Out`,
            r.payment_type AS `Mode of Payment`
        FROM
            reservations AS r
        JOIN
            users AS u ON r.user_id = u.user_id
        JOIN
            rooms AS rm ON r.room_id = rm.room_id
        WHERE
            r.check_in_date = ?";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_date);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Set headers to download the file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="daily_report_' . $selected_date . '.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Output the column headings
    fputcsv($output, ['Name', 'Room Number', 'Total Amount Paid', 'Check In', 'Check Out', 'Mode of Payment']);

    // Output data rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    // Close the output stream
    fclose($output);

    // Close the database connection
    $stmt->close();
    $conn->close();
    exit;
}
?>
