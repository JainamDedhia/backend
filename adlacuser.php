<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Form</title>
    <link rel="stylesheet" href="attstyles.css">
</head>
<body>
    <div class="container">
        <img src="attend.jpeg" alt="Attendance Image">
        <h2>Attendance Form</h2>
        <form id="attendanceForm">
            <div class="form-group">
                <label for="id">ID:</label>
                <input type="text" id="id" name="id" required>
            </div>
            <div class="form-group">
                <label for="dob">Date:</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="">Select</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>
            <!-- Hidden fields for latitude and longitude -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <div class="form-group">
                <input type="button" value="Submit" onclick="recordAttendance()">
            </div>
        </form>
        <div id="message"></div>
        <div id="geofenceStatus" style="margin-top: 20px; font-weight: bold;"></div>
    </div>
    <?php
    session_start();
    
    // Check if the user is not logged in
    if (!isset($_SESSION['username'])) {
        // Redirect unauthorized users to the login page
        header("Location: login.php");
        exit(); // Stop further execution
    }
    ?>
    <script src="attendance.js"></script>
    <script>
        function setTodayDate() {
            var today = new Date().toISOString().split('T')[0];
            document.getElementById('dob').setAttribute('min', today);
            document.getElementById('dob').setAttribute('max', today);
            document.getElementById('dob').value = today;
        }

        function recordAttendance() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    
                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;

                    var formData = new FormData(document.getElementById('attendanceForm'));

                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "record_attendance.php", true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            var response = JSON.parse(xhr.responseText);
                            alert(response.message);
                            if (response.message.includes("Attendance recorded successfully")) {
                                setInterval(checkLocationPeriodically, 10 * 1000); // Start geofencing check
                            }
                        }
                    };
                    xhr.send(formData);

                }, function(error) {
                    document.getElementById('geofenceStatus').innerText = 'Error getting location: ' + error.message;
                });
            } else {
                document.getElementById('geofenceStatus').innerText = 'Geolocation is not supported by this browser.';
            }
        }

        window.onload = setTodayDate;
    </script>
</body>
</html>
