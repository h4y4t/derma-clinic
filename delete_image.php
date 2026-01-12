<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'derma_clinic');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_id'])) {
    $image_id = intval($_POST['image_id']);
    $conn->query("DELETE FROM analyzed_images WHERE id = $image_id");
}

header("Location: profile.php");
exit();
