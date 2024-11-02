<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Styles for black and yellow color scheme */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #2a283c;
            color: black;
        }

        /* Position the home button in the top-right corner */
        .home-container {
            position: fixed;
            top: 13px;
            right: 150px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .home-button {
            display: block;
            background: none;
            border: none;
            color: #FFD700;
            font-size: 24px;
        }

        .home-button:hover {
            color: #FFC107;
        }

        /* Styles for the form */
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 65vh;
            padding-top: 120px;
            /* To account for the home button */
        }

        .form-container {
            max-width: 1200px;
            padding: 20px;
            border-radius: 5px;
            background-color: #e6e6fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 10px;
            display: flex;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            color: black;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
        }

        .form-row .column {
            width: 48%;
            /* Two columns with some space between */
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #333;
            color: #e6e6fa;
            border: 1px solid #FFD700;
            box-sizing: border-box;
        }

        /* Style for submit button */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .form-actions button {
            padding: 10px 20px;
            background-color: #FFD700;
            color: #000;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-actions button:hover {
            background-color: #FFC107;
        }

        .message {
            color: #FFD700;
            text-align: center;
            margin-top: 10px;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <?php include '../auth_check.php'; ?> <!-- Include the authentication check -->

    <!-- Home button in the top-right corner -->
    <div class="home-container" onclick="location.href='../dashboard.php'">
        <button class="home-button"><i class="fas fa-home"></i></button> <!-- Home icon -->
    </div>

    <!-- Main content to center the form -->
    <div class="main-content">
        <div class="form-container">
            <h2>Create User Account</h2>
            <form action="user.php" method="POST">
                <!-- Form row for first column -->
                <div class="form-row">
                    <div class="column">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required>

                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" required>

                        <label for="surname">Surname</label>
                        <input type="text" id="surname" name="surname" required>

                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" required>
                    </div>

                    <!-- Form row for second column -->
                    <div class="column">
                        <label for="birthday">Birthday</label>
                        <input type="date" id="birthday" name="birthday" required>

                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>

                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>

                        <label for="level">Level</label>
                        <select id="level" name="level" required>
                            <option value="HR">HR</option>
                            <option value="CORE">CORE</option>
                            <option value="LOGISTICS">LOGISTICS</option>
                            <option value="FINANCE">FINANCE</option>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>
                </div>

                <!-- Form actions for submit button -->
                <div class="form-actions">
                    <button type="submit" name="register">Create Account</button>
                </div>
            </form>

            <?php
            include '../dbconnect.php';

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
                $first_name = $_POST['first_name'];
                $middle_name = $_POST['middle_name'];
                $surname = $_POST['surname'];
                $address = $_POST['address'];
                $birthday = $_POST['birthday'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $level = $_POST['level'];

                // Hash the password for security
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Generate a reset token as a numeric value (e.g., UNIX timestamp or any custom numeric format)
                $reset_token = 1728713607;  // Example numeric token, replace with your logic to generate the token

                // Set reset_expires to 1 hour from now (3600 seconds)
                $reset_expires = time() + 3600;

                // Prepare the SQL query with reset_token and reset_expires
                $sql = "INSERT INTO usercontrol (first_name, middle_name, surname, address, birthday, username, email, password, level, reset_token, reset_expires) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    // Bind parameters, ensuring that reset_token and reset_expires are treated as integers
                    $stmt->bind_param("sssssssssis", $first_name, $middle_name, $surname, $address, $birthday, $username, $email, $hashed_password, $level, $reset_token, $reset_expires);

                    // Execute the statement and check if it succeeded
                    if ($stmt->execute()) {
                        echo "<p class='message'>User account created successfully!</p>";
                    } else {
                        echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    echo "<p class='error-message'>Error preparing the statement.</p>";
                }

                // Close the database connection
                $conn->close();
            }
            ?>
        </div>
    </div>
</body>

</html>
