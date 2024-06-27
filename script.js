// Function to format current time as HH:MM:SS
function formatTime(date) {
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');
    return `${hours}:${minutes}:${seconds}`;
}

// Function to handle checkout process
function handleCheckout(event) {
    event.preventDefault();

    // Clear session via fetch API
    fetch('clear-session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
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
}

// Event listener for checkout button click
document.getElementById('checkout-link').addEventListener('click', handleCheckout);
document.getElementById('admin-checkout-link').addEventListener('click', handleCheckout);
