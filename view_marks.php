<?php
include('db.php'); // Ensure this file connects to the 'school' database

$students = [];
$marks = [];
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['department']) && isset($_POST['year'])) {
        $department = $_POST['department'];
        $year = $_POST['year'];

        // Fetch students based on the selected department and year
        $stmt = $conn->prepare("SELECT admission_number, name FROM students WHERE department = ? AND year = ?");
        $stmt->bind_param("ss", $department, $year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        } else {
            $message = "No students found for the selected department and year.";
        }

        $stmt->close();
    } elseif (isset($_POST['admission_number'])) {
        $admission_number = $_POST['admission_number'];

        // Fetch marks for the selected student
        $stmt = $conn->prepare("SELECT subject, mark FROM marks WHERE admission_number = ?");
        $stmt->bind_param("s", $admission_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $marks[] = $row;
            }
        } else {
            $message = "No marks found for the selected student.";
        }

        $stmt->close();
    } else {
        $message = "Invalid request.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Marks</title>
    <link rel="stylesheet" href="view-marks.css"> <!-- Link to the CSS file -->
</head>
<body>
    <header>
        <h1>View Marks</h1>
        <a href="teacher-dashboard.html" class="back-button">Back to Dashboard</a>
    </header>
    <main>
        <?php if (empty($students) && empty($marks)): ?>
            <!-- Form to select department and year -->
            <form method="POST" action="">
                <label for="department">Select Department:</label>
                <select id="department" name="department" required>
                    <option value="">--Select Department--</option>
                    <option value="ECE">ECE</option>
                    <option value="CSE">CSE</option>
                    <option value="EEE">EEE</option>
                    <option value="MECH">MECH</option>
                    <option value="CIVIL">CIVIL</option>
                </select>
                <label for="year">Select Year:</label>
                <select id="year" name="year" required>
                    <option value="">--Select Year--</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
                <button type="submit">View Students</button>
            </form>
        <?php elseif (!empty($students) && empty($marks)): ?>
            <!-- Form to select student and view marks -->
            <form method="POST" action="">
                <label for="admission_number">Select Student:</label>
                <select id="admission_number" name="admission_number" required>
                    <option value="">--Select Student--</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo htmlspecialchars($student['admission_number']); ?>">
                            <?php echo htmlspecialchars($student['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">View Marks</button>
            </form>
        <?php elseif (!empty($marks)): ?>
            <!-- Display marks for selected student -->
            <div class="output-container">
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Mark</th>
                    </tr>
                    <?php foreach ($marks as $mark): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($mark['subject']); ?></td>
                            <td><?php echo htmlspecialchars($mark['mark']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>

        <div class="result-message">
            <?php echo $message; ?>
        </div>
    </main>
</body>
</html>
