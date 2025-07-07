<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="attendanc.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Mark Attendance</h1>
    </header>
    <a href="teacher-dashboard.html" class="back-button">Back to Dashboard</a>
    <main>
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

            <button type="submit" name="step" value="show_students">Show Students</button>
        </form>

        <?php if (isset($_POST['step']) && $_POST['step'] == 'show_students'): ?>
            <?php
            include('db.php'); // Include the database connection file

            $department = $_POST['department'];
            $year = $_POST['year'];

            // Fetch students based on department and year
            $stmt = $conn->prepare("SELECT admission_number, name FROM students WHERE department = ? AND year = ?");
            $stmt->bind_param("si", $department, $year);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>

            <form method="POST" action="mark_attendance.php">
                <input type="hidden" name="department" value="<?php echo htmlspecialchars($department); ?>">
                <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>">
                
                <label for="date">Select Date:</label>
                <input type="date" id="date" name="date" required>

                <h2>Mark Attendance</h2>
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Admission Number</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['admission_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td>
                                    <select name="attendance[<?php echo htmlspecialchars($row['admission_number']); ?>]">
                                        <option value="Present">Present</option>
                                        <option value="Absent">Absent</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                    <button type="submit">Save Attendance</button>
                <?php else: ?>
                    <p>No students found for the selected department and year.</p>
                <?php endif; ?>
                <?php $stmt->close(); ?>
                <?php $conn->close(); ?>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
