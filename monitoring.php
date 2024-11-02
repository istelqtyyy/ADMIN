<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'dbconnect.php'; // Ensure this path is correct for your dbconnect.php
include 'auth_check.php';

// Fetch login actions of accounts with level 'ADMIN'
$sql = "SELECT * FROM login WHERE level = 'ADMIN'";

if ($result = $conn->query($sql)) {
    // Check if there are any results
    if ($result->num_rows > 0) {
        echo "<h2>Admin Login Actions</h2>";
        echo "<table border='1' cellspacing='0' cellpadding='10'>";
        echo "<tr>
                <th>ID</th>
                <th>Username</th>
                <th>Date/Time</th>
              </tr>";

        // Fetch associative array and output data
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['username']) . "</td>
                    <td>" . htmlspecialchars($row['login']) . "</td>    
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "No login actions found for ADMIN accounts.";
    }

    // Free result set
    $result->free();
} else {
    echo "Error: " . $conn->error;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Account Monitoring</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #343a40;
            color: #f8f9fa;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ffc107;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #495057;
        }
    </style>
</head>
<body>
    <!-- The PHP code will insert the table here -->
</body>
</html>
