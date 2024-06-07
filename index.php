<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Form</title>
</head>
<body>
    <h2>Attendance Form</h2>
    <form action="process_attendance.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
