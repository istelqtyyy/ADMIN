<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../dbconnect.php';
include '../auth_check.php';

// Insert data into the database after form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $company_name = $_POST['company_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $business_registration = $_POST['business_registration'] ?? '';
    $mayor_permit = $_POST['mayor_permit'] ?? '';
    $tin = $_POST['tin'] ?? '';
    $proof_of_identity = $_POST['proof_of_identity'] ?? '';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert query
    $sql = "INSERT INTO vendors (company_name, email, password, full_name, gender, city, state, business_registration, mayor_permit, tin, proof_of_identity) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and execute statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssss", $company_name, $email, $hashed_password, $full_name, $gender, $city, $state, $business_registration, $mayor_permit, $tin, $proof_of_identity);

        if ($stmt->execute()) {
            echo "Vendor successfully registered!<br>";
            echo "Redirecting to logistics2.php...";
            // header("Location: logistics2.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Registration</title>
    <style>
        /* Basic Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa; /* Light background */
            color: #212529; /* Dark text */
            padding: 20px;
        }

        /* Form Container */
        .form-container {
            max-width: 500px; /* Limit the width of the form */
            margin: 0 auto; /* Center the form */
            background-color: #ffffff; /* White background for the form */
            padding: 20px;
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Heading Style */
        h2 {
            text-align: center; /* Center the heading */
            margin-bottom: 20px; /* Space below the heading */
            color: #007bff; /* Blue color */
        }

        /* Input Field Styling */
        label {
            display: block; /* Block display for labels */
            margin-bottom: 5px; /* Space below labels */
            font-weight: bold; /* Bold text for labels */
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%; /* Full width */
            padding: 10px; /* Padding for input fields */
            margin-bottom: 15px; /* Space below input fields */
            border: 1px solid #ced4da; /* Light border */
            border-radius: 4px; /* Rounded corners */
            font-size: 16px; /* Larger text */
        }

        /* Button Styling */
        button {
            width: 100%; /* Full width button */
            padding: 10px; /* Padding for button */
            background-color: #007bff; /* Blue background */
            color: #ffffff; /* White text */
            border: none; /* Remove border */
            border-radius: 4px; /* Rounded corners */
            font-size: 16px; /* Larger text */
            cursor: pointer; /* Pointer cursor */
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .form-container {
                width: 90%; /* Adjust form width on smaller screens */
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Vendor Registration</h2>
        <form action="" method="POST">
            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" id="company_name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" required>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="city">City:</label>
            <input type="text" name="city" id="city" required>

            <label for="state">State:</label>
            <input type="text" name="state" id="state" required>

            <label for="business_registration">Business Registration:</label>
            <input type="text" name="business_registration" id="business_registration" required>

            <label for="mayor_permit">Mayor's Permit:</label>
            <input type="text" name="mayor_permit" id="mayor_permit" required>

            <label for="tin">TIN:</label>
            <input type="text" name="tin" id="tin" required>

            <label for="proof_of_identity">Proof of Identity:</label>
            <input type="text" name="proof_of_identity" id="proof_of_identity" required>

            <button type="submit">Register Vendor</button>
        </form>
    </div>
</body>

</html>
