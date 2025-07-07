<?php
session_start();
include('db.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['username'];

    // Fetch the current password from the database
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password (without hashing)
    if ($current_password === $db_password) {
        // Check if the new password and confirm password match
        if ($new_password === $confirm_password) {
            // Update the password in the database
            $update_sql = "UPDATE users SET password = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_password, $user_id);
            $update_stmt->execute();
            $update_stmt->close();

            echo "<p>Password changed successfully!</p>";
        } else {
            echo "<p style='color: red;'>New password and confirm password do not match!</p>";
        }
    } else {
        echo "<p style='color: red;'>Current password is incorrect!</p>";
    }
}

$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="change-password.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Change Password</h1>
    </header>
    <main>
        <form action="change_password.php" method="POST">
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit" name="change_password">Change Password</button>
        </form>
    </main>
</body>
</html>
