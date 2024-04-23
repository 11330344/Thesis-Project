<?php
include_once('config.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `user_db` WHERE `username` = '$username'";
    $result = $conn->query($sql);

    if (!$result) {
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['name'] = $row['name'];
                header("Location: user/index.php");
                exit();
            } else {
                echo "Invalid password. Please try again.";
            }
        } else {
            echo "User not found. Please register.";
        }
    }
}

$conn->close();
?>
