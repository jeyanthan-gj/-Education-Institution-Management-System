<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link rel="stylesheet" href="view-students.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <h2>View Students</h2>
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
        </div>

        <?php
        include('db.php'); // Ensure this file connects to the 'school' database

        if (isset($_POST['department']) && isset($_POST['year'])) {
            $department = $_POST['department'];
            $year = $_POST['year'];

            // Fetch students based on the selected department and year
            $stmt = $conn->prepare("SELECT name, admission_number, department, year, mobilenumber FROM students WHERE department = ? AND year = ?");
            $stmt->bind_param("ss", $department, $year);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='output-container'>";
                echo "<table>
                        <tr>
                            <th>Name</th>
                            <th>Admission Number</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Mobile Number</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['admission_number']}</td>
                            <td>{$row['department']}</td>
                            <td>{$row['year']}</td>
                            <td>{$row['mobilenumber']}</td>
                          </tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p>No students found for the selected department and year.</p>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
