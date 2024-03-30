<?php
// Initialize the session
session_start();

// Check if the access token is stored in the session
if (isset($_SESSION['access_token'])) {
    // Get the access token from the session
    $access_token = $_SESSION['access_token'];

    // Define the logout URL for Spotify
    $logout_url = 'https://accounts.spotify.com/logout';

    // Append the access token to the logout URL as a query parameter
    $logout_url .= '?access_token=' . urlencode($access_token);

    // Create a cURL request to log out from Spotify
    $ch = curl_init($logout_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0); // Set timeout to 0 for asynchronous request

    // Execute the cURL request (asynchronous)
    curl_exec($ch);

    // Close cURL resource
    curl_close($ch);

    // Unset session variables
    unset($_SESSION['access_token']);
    $_GET['code']='';

    // Redirect to the login page or any other page after logout
    header("Location: https://accounts.spotify.com/logout"); // Change 'login.php' to your desired logout destination
    exit;

    


} else {
    // Access token not found in session
    echo 'Access token not found.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>
<body>
    <!-- JavaScript code to redirect to the second header after 10 seconds -->
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 10000); // 10 seconds delay
    </script>
</body>
</html>