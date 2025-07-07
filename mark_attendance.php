<?php
include('db.php'); // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance = $_POST['attendance'];
    $date = $_POST['date']; // Capture the selected date

    foreach ($attendance as $admission_number => $status) {
        // Insert attendance only if not already present for the selected date
        $sql = "INSERT INTO attendance (admission_number, date, status) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $admission_number, $date, $status);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the attendance page with a success message, preserving the selected date
    header("Location: attendance.php?date=" . urlencode($date) . "&result=Attendance Saved Successfully");
    exit();
}
