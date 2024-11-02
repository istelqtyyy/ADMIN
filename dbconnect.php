<?php
// Database credentials
$host = 'localhost'; // Use lowercase 'localhost'
$dbname = 'admin_ec_db'; // Your database name
$user = 'admin_ec_root'; // Username, typically 'root'
$pass = 'qouCjCzGs0bw@3mB'; // Empty string if no password

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
