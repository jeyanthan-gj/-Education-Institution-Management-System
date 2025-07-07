<?php
session_start();
include('db.php'); // Ensure this file connects to the 'school' database

// Check if the student is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch the student's admission number (username)
$admission_number = $_SESSION['username'];

$attendance_message = ""; // Variable to hold the attendance message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_date = $_POST['date'];

    // Fetch attendance data for the selected date
    $stmt = $conn->prepare("SELECT status FROM attendance WHERE admission_number = ? AND date = ?");
    $stmt->bind_param("ss", $admission_number, $selected_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $attendance_status = $row['status'];

        // Determine emoji based on attendance status
        $emoji = ($attendance_status === "Present") ? "✅" : "❌";

        // Format the attendance message
        $attendance_message = "<p>Attendance on " . date("d-m-Y", strtotime($selected_date)) . ": <strong>$attendance_status $emoji</strong></p>";
    } else {
        $attendance_message = "<p>No attendance record found for the selected date. 😔</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="atts.css">
</head>
<body>
    <header>
        <h1>View Attendance</h1>
    </header>
    
    <main>
        <form method="POST" action="view_attendances.php">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" required>
            <button type="submit">View Attendance</button>
        </form>

        <!-- Display the attendance message below the form -->
        <div class="attendance-result">
            <?php echo $attendance_message; ?>
        </div>
    </main>
</body>
</html>
