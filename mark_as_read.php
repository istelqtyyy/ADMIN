<?php
include 'dbconnect.php';

if (isset($_GET['id'])) {
    $notificationId = intval($_GET['id']);
    
    $stmt = $conn->prepare("UPDATE form SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $notificationId);
    
    $response = [];
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
    }
    
    $stmt->close();
    echo json_encode($response);
}
?>
