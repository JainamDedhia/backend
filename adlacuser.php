<?php
// Ensure session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Dashboard, Leave Application, Leave Application Dashboard, and Checkout Option</title>
    <link rel="stylesheet" href="adlacuser.css">
</head>
<body>
    <h2>Your attendance has been marked!</h2>
    
    <marquee behavior="alternate" direction="left"><h3>Mark your attendance, view your attendance and leave request/s, fill leave application or simply checkout!</h3></marquee>

    <div class="btn1">
        <a href="attendenceform.php" target="_blank"><button class="adlac">Mark attendance</button></a>
    </div>

    <div class="btn2">
        <a href="dashboard.php" target="_blank"><button class="adlac">Attendance Dashboard</button></a>
    </div>

    <div class="btn3">
        <a href="leaveappform.php" target="_blank"><button class="adlac">Leave Application</button></a>
    </div>

    <div class="btn4">
        <a href="view_leave.php" target="_blank"><button class="adlac">Leave Application Dashboard</button></a>
    </div>

    <div class="btn5">
        <a id="checkout-link" href="checkout-time.html"><button class="adlac">Checkout</button></a>
    </div>

    <script>
    function formatTime(date) {
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const seconds = date.getSeconds().toString().padStart(2, '0');
        return `${hours}:${minutes}:${seconds}`;
    }

    // Event listener to set checkout time in URL on click
    document.getElementById('checkout-link').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default action of anchor tag

        // Clear session via fetch API
        fetch('clear-session-user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ logout: true })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to clear session');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response from clear-session-user.php:', data); // Log the response

            if (data.status === 'success') {
                // Get current time
                const now = new Date();
                const checkoutTime = formatTime(now);

                // Construct URL with checkout time parameter
                const checkoutUrl = `checkout-time.html?time=${encodeURIComponent(checkoutTime)}`;

                // Navigate to checkout-time.html with checkout time in URL
                window.location.href = checkoutUrl;
            } else {
                throw new Error('Failed to clear session');
            }
        })
        .catch(error => {
            console.error('Error clearing session:', error);
            // Handle error as needed
        });
    });
    </script>
</body>
</html>
