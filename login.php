<?php
session_start();
include('db.php'); // Ensure this file connects to the 'school' database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query to fetch user details
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stored_password = $row['password'];
        $role = $row['role'];

        // Compare passwords
        if ($password === $stored_password) {
            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Redirect to the appropriate dashboard based on role
            switch ($role) {
                case 'principal':
                    header("Location: principal-dashboard.html");
                    break;
                case 'teacher':
                    header("Location: teacher-dashboard.html");
                    break;
                case 'student':
                    header("Location: student-dashboard.html");
                    break;
                default:
                    echo "Invalid role.";
                    break;
            }
            exit(); // Ensure no further code is executed
        } else {
            echo "<p style='color: red;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color: red;'>User not found.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
