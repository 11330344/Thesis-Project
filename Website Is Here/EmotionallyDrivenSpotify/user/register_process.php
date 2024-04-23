<?php
include_once('config.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $check_username = "SELECT * FROM user_db WHERE username = '$username'";
    $result = $conn->query($check_username);
    if ($result->num_rows > 0) {
        echo "Username already exists. Please choose a different username.";
    } else {
        // Insert new user into the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user_db (name, username, password) VALUES ('$name', '$username', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            // Registration successful, display alert
            echo '<script>alert("Registration successful!");</script>';
            header("Location: index.php");
            exit(); // Ensure script execution stops after redirection
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
