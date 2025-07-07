<?php
include('db.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $department = $_POST['department'];
    $mobile = $_POST['mobile'];
    $designation = $_POST['designation'];
    $user_id = $_POST['user-id'];

    // Prepare and execute the insert statement for teachers table
    $stmt1 = $conn->prepare("INSERT INTO teachers (name, department, mobile_number, designation, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt1->bind_param("sssss", $name, $department, $mobile, $designation, $user_id);

    // Prepare and execute the insert statement for users table
    $stmt2 = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'teacher')");
    $stmt2->bind_param("ss", $user_id, $mobile); // Assuming mobile number as password

    try {
        $stmt1->execute();
        $stmt2->execute();
        
        if ($stmt1->affected_rows > 0 && $stmt2->affected_rows > 0) {
            $message = "Teacher added successfully!";
        } else {
            $message = "Failed to add teacher.";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }

    $stmt1->close();
    $stmt2->close();
    $conn->close();
    
    // Output JavaScript to show an alert with the message
    echo "<script>alert('$message'); window.location.href='principal-dashboard.html';</script>";
}
?>
