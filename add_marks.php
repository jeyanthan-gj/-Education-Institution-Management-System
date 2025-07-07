<?php
include('db.php'); // Ensure this file connects to the 'school' database

$message = '';
$students = [];
$marks_added = false;

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
    } elseif (isset($_POST['admission_number']) && isset($_POST['subject']) && isset($_POST['mark'])) {
        $admission_number = $_POST['admission_number'];
        $subject = $_POST['subject'];
        $mark = $_POST['mark'];

        // Insert marks into the database
        $stmt = $conn->prepare("INSERT INTO marks (admission_number, subject, mark) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $admission_number, $subject, $mark);

        if ($stmt->execute()) {
            $message = "Marks added successfully.";
            $marks_added = true;
        } else {
            $message = "Error adding marks: " . $stmt->error;
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
    <title>Add Marks</title>
    <link rel="stylesheet" href="add_marks.css"> <!-- Link to the CSS file -->
</head>
<body>
    <header>
        <h1>Add Marks</h1>
        <a href="teacher-dashboard.html" class="back-button">Back to Dashboard</a>
    </header>
    <main>
        <div class="form-container">
            <?php if (!$marks_added): ?>
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

                <?php if (!empty($students)): ?>
                    <!-- Form to add marks -->
                    <form method="POST" action="">
                        <?php foreach ($students as $student): ?>
                            <div class="student-info">
                                <p><?php echo htmlspecialchars($student['name']); ?> (Admission Number: <?php echo htmlspecialchars($student['admission_number']); ?>)</p>
                                <input type="hidden" name="admission_number" value="<?php echo htmlspecialchars($student['admission_number']); ?>">
                                <label for="subject">Subject:</label>
                                <input type="text" id="subject" name="subject" required>
                                <label for="mark">Mark:</label>
                                <input type="number" id="mark" name="mark" min="0" required>
                                <button type="submit">Add Mark</button>
                            </div>
                        <?php endforeach; ?>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="result-message">
            <?php echo $message; ?>
        </div>
    </main>
</body>
</html>
