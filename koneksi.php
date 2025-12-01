<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "pibs_kelompok";

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF8 for proper character encoding
$conn->set_charset("utf8");

// Optional: Display success message (comment out in production)
// echo "Connected successfully";

?>