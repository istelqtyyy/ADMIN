<?php
// Adjust the path according to your directory structure
include '../dbconnect.php'; // Ensure this path is correct
include '../auth_check.php';

// Handle fetching data for editing
$account = []; // Initialize the variable
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    if (!empty($userId) && is_numeric($userId)) {
        $query = "SELECT * FROM usercontrol WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if any record was returned
        if ($result->num_rows > 0) {
            $account = $result->fetch_assoc();
        } else {
            die('No account found with this ID.');
        }
    } else {
        die('ID not provided or invalid.');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST); // Debugging line
    $userId = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $level = $_POST['level'];
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $surname = $_POST['surname'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];

    $validLevels = ['LOGISTICS', 'CORE', 'FINANCE', 'ADMIN']; // Updated
    if (!in_array($level, $validLevels)) {
        die('Invalid level selected.');
    }

    $updateQuery = "UPDATE usercontrol SET username = ?, email = ?, level = ?, first_name = ?, middle_name = ?, surname = ?, address = ?, birthday = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssissi", $username, $email, $level, $firstName, $middleName, $surname, $address, $birthday, $userId);

    if ($stmt->execute()) {
        header("Location: logistics3.php"); // Redirect after successful update
        exit();
    } else {
        die('Failed to update the account: ' . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your existing styles here */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #FFC107;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #FFC107;
        }

        .buttons {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .buttons a {
            text-decoration: none;
            color: #FFC107;
            background-color: #000;
            padding: 10px 15px;
            border: 1px solid #FFC107;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .buttons a:hover {
            background-color: #FFC107;
            color: black;
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
            font-weight: bold;
            color: #FFC107;
        }

        input,
        select {
            padding: 12px;
            margin-bottom: 16px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        button {
            grid-column: span 2;
            padding: 10px;
            background-color: #FFC107;
            color: black;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ffb300;
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
            <a href="logistics3.php"><i class="fas fa-home" style="font-size: 24px;"></i> BACK</a>
        </div>
        <h2>Edit Employee Account</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= htmlspecialchars($account['id'] ?? ''); ?>"> <!-- Ensure ID is sent -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($account['username'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($account['email'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="level">Level</label>
                <select name="level" id="level" required>
                    <option value="LOGISTICS" <?= ($account['level'] ?? '') === 'LOGISTICS' ? 'selected' : ''; ?>>LOGISTICS</option>
                    <option value="CORE" <?= ($account['level'] ?? '') === 'CORE' ? 'selected' : ''; ?>>CORE</option>
                    <option value="FINANCE" <?= ($account['level'] ?? '') === 'FINANCE' ? 'selected' : ''; ?>>FINANCE</option>
                    <option value="ADMIN" <?= ($account['level'] ?? '') === 'ADMIN' ? 'selected' : ''; ?>>ADMIN</option>
                </select>
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($account['first_name'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" value="<?= htmlspecialchars($account['middle_name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" name="surname" id="surname" value="<?= htmlspecialchars($account['surname'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" value="<?= htmlspecialchars($account['address'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="birthday">Birthday</label>
                <input type="date" name="birthday" id="birthday" value="<?= htmlspecialchars($account['birthday'] ?? ''); ?>" required>
            </div>

            <button type="submit">Update Account</button>
        </form>
    </div>
</body>

</html>
