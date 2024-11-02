<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../user/login.php");
    exit();
}
?>
