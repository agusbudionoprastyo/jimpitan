<?php
// Assuming you have a MySQL database connection
$servername = "localhost";
$username = "dafm5634_ag";
$password = "Ag7us777__";
$dbname = "dafm5634_funrun";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
        // Query to fetch data for table
        $sql_data = "UPDATE Funrun SET status = 'unchecked', timestamp = null;";
        $result_data = $conn->query($sql_data);

$conn->close();
?>
