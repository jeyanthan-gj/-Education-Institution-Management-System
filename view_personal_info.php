<?php
session_start();
include('db.php'); // Ensure this file connects to the 'school' database

// Check if the user is logged in and the role is student
if (isset($_SESSION['username']) && $_SESSION['role'] === 'student') {
    // The admission number is the username
    $admission_number = $_SESSION['username'];

    // Fetch student details based on the admission number
    $stmt = $conn->prepare("SELECT name, admission_number, department, year, mobilenumber FROM students WHERE admission_number = ?");
    $stmt->bind_param("s", $admission_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Student Personal Information</title>
            <link rel="stylesheet" href="styless.css"> <!-- Link to the external CSS file -->
        </head>
        <body>
            <div class="container">
                <h2>Personal Information</h2>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                <p><strong>Admission Number:</strong> <?php echo htmlspecialchars($row['admission_number']); ?></p>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($row['department']); ?></p>
                <p><strong>Year:</strong> <?php echo htmlspecialchars($row['year']); ?></p>
                <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($row['mobilenumber']); ?></p>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "No information found.";
    }

    $stmt->close();
} else {
    echo "Unauthorized access.";
}

$conn->close();
?>
