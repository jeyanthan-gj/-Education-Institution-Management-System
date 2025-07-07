<?php
include('db.php'); // Ensure this file connects to the 'school' database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = $_POST['name'];
    $admission_number = $_POST['admission_number'];
    $department = $_POST['department'];
    $year = intval($_POST['year']); // Convert the year to an integer
    $dob = $_POST['dob'];
    $mobile = $_POST['mobile'];

    // Insert student details into the students table
    $stmt = $conn->prepare("INSERT INTO students (name, admission_number, department, year, dob, mobilenumber) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $admission_number, $department, $year, $dob, $mobile);
    $stmt2 = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'student')");
    $stmt2->bind_param("ss", $admission_number, $mobile); // Assuming mobile number as password

    if ($stmt->execute()) {
        if($stmt2->execute())
        echo "Student added successfully.";
    } else {
        echo "Error adding student: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
