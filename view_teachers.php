<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Teachers</title>
    <link rel="stylesheet" href="view_teacher.css"> <!-- Link to the CSS file for styling -->
</head>
<body>
    <header>
        <h1>View Teachers</h1>
        <a href="principal-dashboard.html" class="back-button">Back to Dashboard</a>
    </header>
    <main>
        <div class="form-container">
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
                <button type="submit">View Teachers</button>
            </form>
        </div>

        <?php
        include('db.php'); // Ensure this file connects to the 'school' database

        if (isset($_POST['department'])) {
            $department = $_POST['department'];

            // Fetch teachers based on the selected department
            $stmt = $conn->prepare("SELECT name, department, mobile_number, designation, user_id FROM teachers WHERE department = ?");
            $stmt->bind_param("s", $department);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='output-container'>";
                echo "<table>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Mobile</th>
                            <th>Designation</th>
                            <th>ID Number</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                             <td>{$row['name']}</td>
                             <td>{$row['department']}</td>
                             <td>{$row['mobile_number']}</td>
                             <td>{$row['designation']}</td>
                             <td>{$row['user_id']}</td>
                          </tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p>No teachers found for the selected department.</p>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>
    </main>
</body>
</html>
