<?php
include 'dbconnect.php';
include 'auth_check.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert form submission into the form table
    $stmt = $conn->prepare("INSERT INTO form (name, email, message, is_read) VALUES (?, ?, ?, 0)");
    $stmt->bind_param('sss', $name, $email, $message);

    if ($stmt->execute()) {
        // Create a notification message
        $notif_message = !empty($name) ? "New application form submitted by " . htmlspecialchars($name) : "New application form submitted";

        // Insert notification into the form table with the message, setting is_read to 0
        $notif_stmt = $conn->prepare("INSERT INTO form (message, is_read, created_at) VALUES (?, 0, NOW())");
        $notif_stmt->bind_param('s', $notif_message);
        $notif_stmt->execute();
        $notif_stmt->close();

        $success = "Form submitted successfully! You will be redirected to the notifications page.";
        // Redirect to notifications.php after successful submission
        header("Location: notification.php");
        exit();
    } else {
        $error = "Failed to submit the form.";
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
</head>

<body>
    <h2>Application Form</h2>

    <form method="POST" action="applicationform.php">
        <label for="name">Name:</label><br>
        <input type="text" name="name" id="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br><br>

        <label for="message">Message:</label><br>
        <textarea name="message" id="message" rows="5" required></textarea><br><br>

        <button type="submit">Submit</button>
    </form>

    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php elseif ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>

</html>