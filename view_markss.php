<?php
session_start();
include('db.php'); // Ensure this file connects to the 'school' database

// Check if the user is logged in and the role is student
if (isset($_SESSION['username']) && $_SESSION['role'] === 'student') {
    // The admission number is the username
    $admission_number = $_SESSION['username'];

    // Fetch student marks based on the admission number
    $stmt = $conn->prepare("SELECT subject, mark FROM marks WHERE admission_number = ?");
    $stmt->bind_param("s", $admission_number);
    $stmt->execute();
    $result = $stmt->get_result();

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Marks</title>
        <a href="student-dashboard.html" class="back-button">Back to Dashboard</a>
        <link rel="stylesheet" href="sstyles.css"> <!-- Make sure this path is correct -->
        <style>
            /* Inline CSS for demonstration; ideally, move to an external stylesheet */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 800px;
                margin: 50px auto;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
            }
            h2 {
                text-align: center;
                margin-bottom: 20px;
                color: #333;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            table, th, td {
                border: 1px solid #ddd;
            }
            th, td {
                padding: 10px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            .no-records {
                text-align: center;
                color: #555;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Marks Information</h2>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Mark</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><?php echo htmlspecialchars($row['mark']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p class="no-records">No marks found.</p>
            <?php endif; ?>
        </div>
    </body>
    </html>
    <?php

    $stmt->close();
} else {
    echo "Unauthorized access.";
}

$conn->close();
?>
