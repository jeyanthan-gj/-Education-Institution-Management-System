<?php
$servername = "localhost"; // Your MySQL server address
$username = "root";        // Your MySQL username
$password = "jeyanthangj2004@";            // Your MySQL password
$dbname = "school"; // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
