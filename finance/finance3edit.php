<?php
include '../dbconnect.php';
include '../auth_check.php';

// Check if id is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch account details based on id
    $query = "SELECT * FROM usercontrol WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('Query preparation failed: ' . $conn->error);
    }

    $stmt->bind_param('i', $id); // 'i' means the parameter is an integer
    $stmt->execute();
    $result = $stmt->get_result();
    $account = $result->fetch_assoc();

    if (!$account) {
        die('Account not found.');
    }
} else {
    die('ID not provided.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $level = $_POST['level'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $surname = $_POST['surname'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];

    // Update the account details
    $updateQuery = "UPDATE usercontrol 
                    SET username = ?, 
                        email = ?, 
                        level = ?, 
                        first_name = ?, 
                        middle_name = ?, 
                        surname = ?, 
                        address = ?, 
                        birthday = ? 
                    WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die('Update query preparation failed: ' . $conn->error);
    }

    $stmt->bind_param('ssssssssi', $username, $email, $level, $first_name, $middle_name, $surname, $address, $birthday, $id);

    if ($stmt->execute()) {
        // Redirect back to the finance accounts page after successful update
        header("Location: finance3.php");
        exit();
    } else {
        die('Failed to update the account: ' . $stmt->error);
    }
}

// Add additional checks for the keys before using them
$username = isset($account['username']) ? htmlspecialchars($account['username']) : '';
$email = isset($account['email']) ? htmlspecialchars($account['email']) : '';
$level = isset($account['level']) ? $account['level'] : '';
$first_name = isset($account['first_name']) ? htmlspecialchars($account['first_name']) : '';
$middle_name = isset($account['middle_name']) ? htmlspecialchars($account['middle_name']) : '';
$surname = isset($account['surname']) ? htmlspecialchars($account['surname']) : '';
$address = isset($account['address']) ? htmlspecialchars($account['address']) : '';
$birthday = isset($account['birthday']) ? $account['birthday'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .buttons {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .buttons a {
            text-decoration: none;
            color: #fff;
            background-color: #ffc107;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .buttons a:hover {
            background-color: #ffca2c;
        }

        .buttons a i {
            margin-right: 8px;
        }

        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        input,
        select {
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            transition: border 0.3s ease;
        }

        input:focus,
        select:focus {
            border-color: #ffc107;
            outline: none;
        }

        button {
            grid-column: span 2;
            padding: 12px;
            font-size: 18px;
            background-color: #ffc107;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ffca2c;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="buttons">
            <a href="finance3.php"><i class="fas fa-arrow-left"></i> BACK</a>
        </div>
        <h2>Edit Employee Account</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?= $username; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= $email; ?>" required>
            </div>

            <div class="form-group">
                <label for="level">Level</label>
                <select name="level" id="level" required>
                    <option value="HR" <?= $level === 'HR' ? 'selected' : ''; ?>>HR</option>
                    <option value="LOGISTICS" <?= $level === 'LOGISTICS' ? 'selected' : ''; ?>>LOGISTICS</option>
                    <option value="CORE" <?= $level === 'CORE' ? 'selected' : ''; ?>>CORE</option>
                    <option value="FINANCE" <?= $level === 'FINANCE' ? 'selected' : ''; ?>>FINANCE</option>
                </select>
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?= $first_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" value="<?= $middle_name; ?>">
            </div>

            <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" name="surname" id="surname" value="<?= $surname; ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" value="<?= $address; ?>" required>
            </div>

            <div class="form-group">
                <label for="birthday">Birthday</label>
                <input type="date" name="birthday" id="birthday" value="<?= $birthday; ?>" required>
            </div>

            <button type="submit">Update Account</button>
        </form>
    </div>
</body>
</html>
