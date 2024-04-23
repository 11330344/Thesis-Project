<?php
session_start();
// Database configuration
$host = 'localhost'; // Change this if your MySQL server is on a different host
$dbname = 'EDSdb';
$username = 'root';
$password = '';

// Establish a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>