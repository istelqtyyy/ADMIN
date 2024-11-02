<?php
include 'auth_check.php';
include 'dbconnect.php';

// Fetch all notifications, ordered by creation date
$stmt = $conn->prepare("SELECT * FROM form ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Count unread notifications
$unreadCountStmt = $conn->prepare("SELECT COUNT(*) FROM form WHERE is_read = 0");
$unreadCountStmt->execute();
$unreadCountResult = $unreadCountStmt->get_result();
$unreadCount = $unreadCountResult->fetch_row()[0];
$unreadCountStmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        /* Your existing styles here... */

        .notification-list {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            max-width: 600px;
            max-height: 400px;
            background-color: #f9f9f9;
            overflow-y: auto;
        }

        .notification-item {
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .dashboard-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .dashboard-btn:hover {
            background-color: #0056b3;
        }

        /* Styles for the toast notification */
        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
        }

        .toast.show {
            visibility: visible;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }

            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }

            to {
                bottom: 0;
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <h2>Notifications (<span id="notification-count"><?php echo $unreadCount; ?></span>)</h2>

    <div class="notification-list">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item" style="background-color: <?php echo ($notification['is_read'] ? 'cyan' : 'green'); ?>;" 
                     data-id="<?php echo $notification['id']; ?>">
                    <?php echo htmlspecialchars($notification['message']); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
    <!-- Button to go to Application Form -->
    <a href="applicationform.php" class="application-form-btn">Go to Application Form</a>

    <div id="toast" class="toast">New Notification!</div>

    <script>
        const notificationCountElem = document.getElementById("notification-count");

        function showToast(message) {
            const toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = "toast show";

            setTimeout(function() {
                toast.className = toast.className.replace("show", "");
            }, 3000);
        }

        function decreaseNotificationCount() {
            let count = parseInt(notificationCountElem.textContent);
            if (count > 0) {
                notificationCountElem.textContent = count - 1;
            }
        }

        function increaseNotificationCount() {
            let count = parseInt(notificationCountElem.textContent);
            notificationCountElem.textContent = count++;
        }

        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                this.style.backgroundColor = 'cyan';

                // Mark notification as read in the database
                const notificationId = this.getAttribute("data-id");
                fetch(`mark_as_read.php?id=${notificationId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            decreaseNotificationCount();
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });

        <?php if (!empty($notifications)): ?>
            showToast("You have new notifications!");
            increaseNotificationCount();
        <?php endif; ?>
    </script>
</body>

</html>
