<?php
include('db.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the id_number from the POST request
    $id_number = $_POST['id_number']; // Ensure this matches the name attribute in the HTML form

    if (!empty($id_number)) {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Prepare and execute the delete statement for the teachers table
            $stmt1 = $conn->prepare("DELETE FROM teachers WHERE user_id = ?");
            $stmt1->bind_param("s", $id_number);
            $stmt1->execute();

            // Prepare and execute the delete statement for the users table
            $stmt2 = $conn->prepare("DELETE FROM users WHERE username = ? AND role = 'teacher'");
            $stmt2->bind_param("s", $id_number);
            $stmt2->execute();

            // Commit transaction
            $conn->commit();

            // Check if any rows were affected in either table
            if ($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0) {
                $message = "Teacher removed successfully!";
            } else {
                $message = "No teacher found with that ID.";
            }

            $stmt1->close();
            $stmt2->close();
        } catch (Exception $e) {
            // Rollback transaction if something goes wrong
            $conn->rollback();
            $message = "Failed to remove teacher: " . $e->getMessage();
        }

        $conn->close();

        // Redirect with result message
        header("Location: principal-dashboard.html?result=" . urlencode($message));
        exit();
    } else {
        echo "ID number is required.";
    }
}
?>
