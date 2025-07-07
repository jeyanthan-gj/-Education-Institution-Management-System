<?php
include('db.php'); // Include the database connection file

// Initialize variables
$selected_department = isset($_GET['department']) ? $_GET['department'] : '';
$selected_year = isset($_GET['year']) ? $_GET['year'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// SQL query to fetch departments and years for the selection form
$departments_sql = "SELECT DISTINCT department FROM students";
$years_sql = "SELECT DISTINCT year FROM students";

// Initialize result and statement variables
$result = null;
$stmt = null;

if ($selected_department && $selected_year) {
    // Fetch attendance records for the selected department, year, and date
    $sql = "SELECT s.admission_number, s.name, s.department, s.year, a.status
            FROM students s
            LEFT JOIN attendance a ON s.admission_number = a.admission_number AND a.date = ?
            WHERE s.department = ? AND s.year = ?
            ORDER BY s.admission_number";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sss", $date, $selected_department, $selected_year);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="view_attendance.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>View Attendance</h1>
        <a href="teacher-dashboard.html" class="back-button">Back to Dashboard</a>
    </header>
    <main>
        <!-- Department and Year Form -->
        <form method="GET" action="view_attendance.php">
            <label for="department">Select Department:</label>
            <select name="department" id="department" required>
                <option value="">-- Select Department --</option>
                <?php
                $departments_result = $conn->query($departments_sql);
                while ($row = $departments_result->fetch_assoc()) {
                    $selected = ($row['department'] === $selected_department) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['department']) . "' $selected>" . htmlspecialchars($row['department']) . "</option>";
                }
                ?>
            </select>
            <label for="year">Select Year:</label>
            <select name="year" id="year" required>
                <option value="">-- Select Year --</option>
                <?php
                $years_result = $conn->query($years_sql);
                while ($row = $years_result->fetch_assoc()) {
                    $selected = ($row['year'] === $selected_year) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['year']) . "' $selected>" . htmlspecialchars($row['year']) . "</option>";
                }
                ?>
            </select>
            <label for="date">Select Date:</label>
            <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($date); ?>" required>
            <button type="submit">View</button>
        </form>

        <!-- Display Attendance Records -->
        <?php if ($result !== null): ?>
            <table>
                <thead>
                    <tr>
                        <th>Admission Number</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Year</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status = $row['status'] ? $row['status'] : 'Not Marked'; // If status is NULL, display "Not Marked"
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['admission_number']) . "</td>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['department']) . "</td>
                                    <td>" . htmlspecialchars($row['year']) . "</td>
                                    <td>" . htmlspecialchars($status) . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No attendance records found for this date.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>

<?php
// Close the statement and connection if they were created
if ($stmt) {
    $stmt->close();
}
$conn->close(); // Close the database connection
?>
